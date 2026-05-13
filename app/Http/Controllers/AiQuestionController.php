<?php

namespace App\Http\Controllers;

use App\Models\Concept;
use App\Models\GeneratedQuestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

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

        $prompt = "Génère exactement 5 questions d'entretien techniques en français pour ce concept.\n"
            . "Titre: {$concept->title}\n"
            . "Explication: {$concept->explanation}\n"
            . "Difficulté: {$concept->difficultyLabel()}\n"
            . "Statut de maîtrise: {$concept->statusLabel()}\n"
            . "Format attendu: {\"questions\":[\"...\",\"...\",\"...\",\"...\",\"...\"]}";

        try {
            $response = Http::acceptJson()
                ->withToken($apiKey)
                ->timeout(30)
                ->post($url, [
                    'model' => $model,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'Tu es un recruteur backend Laravel. Génère uniquement du JSON valide sans markdown ni bloc de code.'
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                    'temperature' => 0.7,
                    'response_format' => ['type' => 'json_object']
                ]);

            if (!$response->successful()) {
                return back()->with('error', 'Le service IA est indisponible. Réessayez plus tard.');
            }

            $content = $response->json('choices.0.message.content');

            $content = preg_replace('/^```json\s*/', '', $content);
            $content = preg_replace('/```$/', '', $content);
            $content = trim($content);

            $data = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->with('error', 'La réponse IA est invalide. Aucune génération n\'a été sauvegardée.');
            }

            $questions = $data['questions'] ?? $data;

            if (!is_array($questions) || count($questions) !== 5) {
                return back()->with('error', 'La réponse ne contient pas exactement 5 questions.');
            }

            $questions = array_values(array_filter($questions, fn($q) => is_string($q) && !empty(trim($q))));

            if (count($questions) !== 5) {
                return back()->with('error', 'La réponse ne contient pas exactement 5 questions valides.');
            }

            $concept->generatedQuestions()->create([
                'questions' => $questions
            ]);

            return back()->with('success', '5 questions générées avec succès.');

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return back()->with('error', 'Le service IA est indisponible. Réessayez plus tard.');
        } catch (\Illuminate\Http\Client\RequestException $e) {
            return back()->with('error', 'Le service IA est indisponible. Réessayez plus tard.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Une erreur est survenue. Aucune génération n\'a été sauvegardée.');
        }
    }

    public function destroy(GeneratedQuestion $generatedQuestion): RedirectResponse
    {
        $generatedQuestion->load('concept.domain');

        if ($generatedQuestion->concept->domain->user_id !== auth()->id()) {
            abort(403);
        }

        $generatedQuestion->delete();

        return back()->with('success', 'Génération supprimée.');
    }
}