<?php

namespace App\Http\Controllers;

use App\Models\Concept;
use App\Models\GeneratedQuestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiQuestionController extends Controller
{
    public function generate(Concept $concept): RedirectResponse
    {
        $concept->load('domain');

        if ($concept->domain->user_id !== auth()->id()) {
            abort(403);
        }

        $apiKey = config('services.groq.key');

        if (empty($apiKey)) {
            return back()->with('error', 'Configuration IA manquante.');
        }

        $url = config('services.groq.url');
        $model = config('services.groq.model');

        $prompt = "Genere exactement 5 questions d'entretien techniques en francais pour ce concept.\n"
            . "Titre: {$concept->title}\n"
            . "Explication: {$concept->explanation}\n"
            . "Difficulte: {$concept->difficultyLabel()}\n"
            . "Statut de maitrise: {$concept->statusLabel()}\n"
            . "Reponds uniquement avec du JSON valide au format: {\"questions\":[\"Q1\",\"Q2\",\"Q3\",\"Q4\",\"Q5\"]}";

        try {
            $response = Http::acceptJson()
                ->withToken($apiKey)
                ->timeout(30)
                ->post($url, [
                    'model' => $model,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'Tu es un recruteur backend Laravel. Reponds uniquement avec du JSON valide au format: {"questions":["Q1","Q2","Q3","Q4","Q5"]}'
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                    'temperature' => 0.7
                ]);

            $status = $response->status();

            Log::channel('daily')->info('Groq API response', [
                'user_id' => auth()->id(),
                'concept_id' => $concept->id,
                'status' => $status,
            ]);

            if (!$response->successful()) {
                $errorData = $response->json('error') ?? [];
                $errorMessage = $errorData['message'] ?? $errorData['type'] ?? 'Unknown error';

                Log::channel('daily')->warning('Groq API error', [
                    'user_id' => auth()->id(),
                    'concept_id' => $concept->id,
                    'status' => $status,
                    'error_type' => $errorData['type'] ?? null,
                    'error_message' => $errorMessage,
                ]);

                return match (true) {
                    $status === 400 => back()->with('error', 'Requete IA invalide. Verifiez le modele ou le format demande.'),
                    in_array($status, [401, 403]) => back()->with('error', 'Cle IA invalide ou expiree.'),
                    $status === 404 => back()->with('error', 'Modele IA ou URL invalide.'),
                    $status === 429 => back()->with('error', 'Limite de requetes IA atteinte. Reessayez plus tard.'),
                    $status >= 500 => back()->with('error', 'Le service IA est indisponible. Reessayez plus tard.'),
                    default => back()->with('error', "Erreur IA (HTTP $status). Reessayez plus tard."),
                };
            }

            $responseData = $response->json();
            $content = $responseData['choices'][0]['message']['content'] ?? null;

            if (empty($content)) {
                Log::channel('daily')->error('Groq empty content', [
                    'user_id' => auth()->id(),
                    'concept_id' => $concept->id,
                    'response_keys' => array_keys($responseData),
                ]);
                return back()->with('error', 'Reponse IA invalide. Aucune generation n\'a ete sauvegardee.');
            }

            $content = trim($content);

            if (preg_match('/^```(?:json)?\s*\n?(.*?)\n?\s*```$/s', $content, $matches)) {
                $content = $matches[1];
            }

            $data = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::channel('daily')->error('Groq JSON parse failed', [
                    'user_id' => auth()->id(),
                    'concept_id' => $concept->id,
                    'json_error' => json_last_error_msg(),
                    'content_preview' => mb_substr($content, 0, 500),
                ]);
                return back()->with('error', 'La reponse IA est invalide. Aucune generation n\'a ete sauvegardee.');
            }

            if (!isset($data['questions']) || !is_array($data['questions'])) {
                Log::channel('daily')->error('Groq missing questions key', [
                    'user_id' => auth()->id(),
                    'concept_id' => $concept->id,
                    'data_keys' => array_keys($data),
                ]);
                return back()->with('error', 'La reponse IA est invalide. Aucune generation n\'a ete sauvegardee.');
            }

            $questions = array_values(array_filter(
                $data['questions'],
                fn($q) => is_string($q) && trim($q) !== ''
            ));

            if (count($questions) !== 5) {
                Log::channel('daily')->warning('Groq question count mismatch', [
                    'user_id' => auth()->id(),
                    'concept_id' => $concept->id,
                    'count' => count($questions),
                ]);
                return back()->with('error', 'La reponse ne contient pas exactement 5 questions valides.');
            }

            $concept->generatedQuestions()->create([
                'questions' => $questions
            ]);

            return back()->with('success', '5 questions generezes avec succes.');

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::channel('daily')->error('Groq connection error', [
                'user_id' => auth()->id(),
                'concept_id' => $concept->id,
                'exception' => get_class($e),
            ]);
            return back()->with('error', 'Le service IA est indisponible. Reessayez plus tard.');

        } catch (\Illuminate\Http\Client\RequestException $e) {
            Log::channel('daily')->error('Groq request exception', [
                'user_id' => auth()->id(),
                'concept_id' => $concept->id,
                'exception' => get_class($e),
                'message' => $e->getMessage(),
            ]);
            return back()->with('error', 'Le service IA est indisponible. Reessayez plus tard.');

        } catch (\Throwable $e) {
            Log::channel('daily')->error('Groq unexpected error', [
                'user_id' => auth()->id(),
                'concept_id' => $concept->id,
                'exception' => get_class($e),
                'message' => $e->getMessage(),
            ]);
            return back()->with('error', 'Une erreur est survenue. Aucune generation n\'a ete sauvegardee.');
        }
    }

    public function destroy(GeneratedQuestion $generatedQuestion): RedirectResponse
    {
        $generatedQuestion->load('concept.domain');

        if ($generatedQuestion->concept->domain->user_id !== auth()->id()) {
            abort(403);
        }

        $generatedQuestion->delete();

        return back()->with('success', 'Generation supprimee.');
    }
}