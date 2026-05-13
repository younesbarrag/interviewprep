<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreConceptRequest;
use App\Http\Requests\UpdateConceptRequest;
use App\Models\Concept;
use App\Models\Domain;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConceptController extends Controller
{
    public function index(Request $request, Domain $domain): View
    {
        if ($domain->user_id !== auth()->id()) {
            abort(403);
        }

        $query = $domain->concepts();

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('difficulty') && $request->difficulty !== '') {
            $query->where('difficulty', $request->difficulty);
        }

        $concepts = $query->orderBy('created_at', 'desc')->get();

        return view('concepts.index', compact('domain', 'concepts'));
    }

    public function create(Domain $domain): View
    {
        if ($domain->user_id !== auth()->id()) {
            abort(403);
        }

        return view('concepts.create', compact('domain'));
    }

    public function store(StoreConceptRequest $request, Domain $domain): RedirectResponse
    {
        if ($domain->user_id !== auth()->id()) {
            abort(403);
        }

        $domain->concepts()->create($request->validated());

        return redirect()->route('domains.concepts.index', $domain)
            ->with('success', 'Concept créé avec succès.');
    }

    public function show(Concept $concept): View
    {
        $concept->load('domain');

        if ($concept->domain->user_id !== auth()->id()) {
            abort(403);
        }

        $concept->loadCount('generatedQuestions');

        return view('concepts.show', compact('concept'));
    }

    public function edit(Concept $concept): View
    {
        $concept->load('domain');

        if ($concept->domain->user_id !== auth()->id()) {
            abort(403);
        }

        return view('concepts.edit', compact('concept'));
    }

    public function update(UpdateConceptRequest $request, Concept $concept): RedirectResponse
    {
        $concept->load('domain');

        if ($concept->domain->user_id !== auth()->id()) {
            abort(403);
        }

        $concept->update($request->validated());

        return redirect()->route('concepts.show', $concept)
            ->with('success', 'Concept mis à jour avec succès.');
    }

    public function updateStatus(Request $request, Concept $concept): RedirectResponse
    {
        $concept->load('domain');

        if ($concept->domain->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:to_review,in_progress,mastered',
        ]);

        $concept->update(['status' => $request->status]);

        return back()->with('success', 'Statut mis à jour.');
    }

    public function destroy(Concept $concept): RedirectResponse
    {
        $concept->load('domain');

        if ($concept->domain->user_id !== auth()->id()) {
            abort(403);
        }

        $concept->delete();

        return back()->with('success', 'Concept archivé.');
    }

    public function archived(): View
    {
        $concepts = Concept::onlyTrashed()
            ->whereHas('domain', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->with('domain')
            ->orderBy('deleted_at', 'desc')
            ->get();

        return view('concepts.archived', compact('concepts'));
    }

    public function restore(int $id): RedirectResponse
    {
        $concept = Concept::onlyTrashed()
            ->whereHas('domain', fn($q) => $q->where('user_id', auth()->id()))
            ->findOrFail($id);

        $concept->restore();

        return redirect()->route('concepts.archived')
            ->with('success', 'Concept restauré.');
    }
}