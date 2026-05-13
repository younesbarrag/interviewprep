<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDomainRequest;
use App\Http\Requests\UpdateDomainRequest;
use App\Models\Domain;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DomainController extends Controller
{
    public function index(): View
    {
        $domains = Domain::where('user_id', auth()->id())
            ->withCount('concepts')
            ->get()
            ->map(function ($domain) {
                $domain->mastered_count = $domain->concepts()
                    ->where('status', 'mastered')
                    ->count();
                return $domain;
            });

        return view('domains.index', compact('domains'));
    }

    public function create(): View
    {
        return view('domains.create');
    }

    public function store(StoreDomainRequest $request): RedirectResponse
    {
        Domain::create([
            'name' => $request->validated('name'),
            'color' => $request->validated('color'),
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('domains.index')
            ->with('success', 'Domaine créé avec succès.');
    }

    public function show(Domain $domain): View
    {
        if ($domain->user_id !== auth()->id()) {
            abort(403);
        }

        $domain->loadCount('concepts');
        $domain->mastered_count = $domain->concepts()
            ->where('status', 'mastered')
            ->count();

        $concepts = $domain->concepts()->orderBy('created_at', 'desc')->get();

        return view('domains.show', compact('domain', 'concepts'));
    }

    public function edit(Domain $domain): View
    {
        if ($domain->user_id !== auth()->id()) {
            abort(403);
        }

        return view('domains.edit', compact('domain'));
    }

    public function update(UpdateDomainRequest $request, Domain $domain): RedirectResponse
    {
        if ($domain->user_id !== auth()->id()) {
            abort(403);
        }

        $domain->update($request->validated());

        return redirect()->route('domains.show', $domain)
            ->with('success', 'Domaine mis à jour avec succès.');
    }

    public function destroy(Domain $domain): RedirectResponse
    {
        if ($domain->user_id !== auth()->id()) {
            abort(403);
        }

        $domain->delete();

        return redirect()->route('domains.index')
            ->with('success', 'Domaine supprimé avec succès.');
    }
}