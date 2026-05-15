@extends('layouts.app')

@section('title', 'Concepts - ' . $domain->name)

@section('content')
<div class="py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="mb-2">
            <a href="{{ route('domains.show', $domain) }}" class="inline-flex items-center gap-2 text-indigo-400 hover:text-indigo-300 transition text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Retour au domaine
            </a>
        </div>

        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-100">Concepts</h1>
                <p class="text-sm text-slate-500 mt-1 flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full" style="background-color: {{ $domain->color }}"></span>
                    {{ $domain->name }}
                </p>
            </div>
            <a href="{{ route('domains.concepts.create', $domain) }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-5 py-2.5 rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Nouveau concept
            </a>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-lg bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-lg bg-red-500/10 border border-red-500/30 text-red-300 text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-slate-800/70 rounded-xl p-4 border border-slate-700">
            <form method="GET" class="flex flex-wrap gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1">Statut</label>
                    <select name="status" class="rounded-lg border border-slate-600 bg-slate-700 text-slate-100 text-sm px-3 py-2 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        <option value="">Tous</option>
                        <option value="to_review" {{ request('status') === 'to_review' ? 'selected' : '' }}>A revoir</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>En cours</option>
                        <option value="mastered" {{ request('status') === 'mastered' ? 'selected' : '' }}>Maitrise</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1">Difficulte</label>
                    <select name="difficulty" class="rounded-lg border border-slate-600 bg-slate-700 text-slate-100 text-sm px-3 py-2 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        <option value="">Toutes</option>
                        <option value="junior" {{ request('difficulty') === 'junior' ? 'selected' : '' }}>Junior</option>
                        <option value="mid" {{ request('difficulty') === 'mid' ? 'selected' : '' }}>Mid</option>
                        <option value="senior" {{ request('difficulty') === 'senior' ? 'selected' : '' }}>Senior</option>
                    </select>
                </div>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2 rounded-lg transition">Filtrer</button>
                @if(request('status') || request('difficulty'))
                    <a href="{{ route('domains.concepts.index', $domain) }}" class="text-slate-400 hover:text-slate-300 py-2 transition">Effacer</a>
                @endif
            </form>
        </div>

        @if($concepts->isEmpty())
            <div class="bg-slate-800/70 rounded-xl p-10 text-center border border-slate-700">
                <h3 class="text-lg font-medium text-slate-100 mb-2">Aucun concept</h3>
                <p class="text-slate-400 mb-6">Ce domaine ne contient pas de concepts.</p>
                <a href="{{ route('domains.concepts.create', $domain) }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-5 py-2.5 rounded-lg transition">
                    Creer votre premier concept
                </a>
            </div>
        @else
            <div class="bg-slate-800/70 rounded-xl border border-slate-700 overflow-hidden">
                <table class="min-w-full">
                    <thead class="bg-slate-700/70">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Titre</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Difficulte</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Statut</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/50">
                        @foreach($concepts as $concept)
                            <tr class="hover:bg-slate-700/30 transition">
                                <td class="px-4 py-3">
                                    <a href="{{ route('concepts.show', $concept) }}" class="text-indigo-400 hover:text-indigo-300 font-medium transition">
                                        {{ $concept->title }}
                                    </a>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-500/20 text-blue-400">
                                        {{ $concept->difficultyLabel() }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <form action="{{ route('concepts.updateStatus', $concept) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" onchange="this.form.submit()" class="text-xs rounded border border-slate-600 bg-slate-700 text-slate-100 px-2 py-1 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                            <option value="to_review" {{ $concept->status === 'to_review' ? 'selected' : '' }}>A revoir</option>
                                            <option value="in_progress" {{ $concept->status === 'in_progress' ? 'selected' : '' }}>En cours</option>
                                            <option value="mastered" {{ $concept->status === 'mastered' ? 'selected' : '' }}>Maitrise</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex gap-2">
                                        <a href="{{ route('concepts.show', $concept) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium px-2.5 py-1.5 rounded-lg transition">Voir</a>
                                        <a href="{{ route('concepts.edit', $concept) }}" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium px-2.5 py-1.5 rounded-lg transition">Modifier</a>
                                        <form action="{{ route('concepts.destroy', $concept) }}" method="POST" class="inline" onsubmit="return confirm('Archiver ce concept ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-xs font-medium px-2.5 py-1.5 rounded-lg transition">Archiver</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <div>
            <a href="{{ route('concepts.archived') }}" class="inline-flex items-center gap-2 text-slate-400 hover:text-slate-300 transition text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                </svg>
                Voir les concepts archives
            </a>
        </div>
    </div>
</div>
@endsection