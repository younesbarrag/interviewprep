@extends('layouts.app')

@section('title', $domain->name)

@section('content')
<div class="py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="mb-2">
            <a href="{{ route('domains.index') }}" class="inline-flex items-center gap-2 text-indigo-400 hover:text-indigo-300 transition text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Retour aux domaines
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

        <div class="bg-slate-800/70 rounded-xl p-6 border border-slate-700">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div class="flex items-center gap-3">
                    <span class="w-4 h-4 rounded-full" style="background-color: {{ $domain->color }}"></span>
                    <h1 class="text-xl font-bold text-slate-100">{{ $domain->name }}</h1>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('domains.edit', $domain) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg transition">Modifier</a>
                    <form action="{{ route('domains.destroy', $domain) }}" method="POST" onsubmit="return confirm('Supprimer ce domaine ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-medium px-4 py-2 rounded-lg transition">Supprimer</button>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-slate-700/50 rounded-lg p-4 border border-slate-700">
                    <p class="text-xs text-slate-500 mb-1">Total concepts</p>
                    <p class="text-2xl font-bold text-slate-100">{{ $domain->concepts_count }}</p>
                </div>
                <div class="bg-slate-700/50 rounded-lg p-4 border border-slate-700">
                    <p class="text-xs text-slate-500 mb-1">Maîtrisés</p>
                    <p class="text-2xl font-bold text-green-400">{{ $domain->mastered_count }}</p>
                </div>
            </div>

            <div class="border-t border-slate-700 pt-6">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-4">
                    <h2 class="text-base font-semibold text-slate-100">Concepts</h2>
                    <a href="{{ route('domains.concepts.create', $domain) }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2 rounded-lg transition text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Nouveau concept
                    </a>
                </div>

                @if($concepts->isEmpty())
                    <div class="bg-slate-700/30 rounded-lg p-6 text-center border border-slate-700">
                        <p class="text-slate-400 mb-2">Aucun concept pour le moment.</p>
                        <a href="{{ route('domains.concepts.create', $domain) }}" class="text-indigo-400 hover:text-indigo-300 text-sm transition">Creer votre premier concept</a>
                    </div>
                @else
                    <div class="bg-slate-700/30 rounded-lg border border-slate-700 overflow-hidden">
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
                                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                                @if($concept->status === 'to_review') bg-orange-500/20 text-orange-400
                                                @elseif($concept->status === 'in_progress') bg-amber-500/20 text-amber-400
                                                @else bg-green-500/20 text-green-400 @endif">
                                                {{ $concept->statusLabel() }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex gap-2">
                                                <a href="{{ route('concepts.show', $concept) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium px-2.5 py-1.5 rounded-lg transition">Voir</a>
                                                <a href="{{ route('concepts.edit', $concept) }}" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium px-2.5 py-1.5 rounded-lg transition">Modifier</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <div class="mt-4 pt-4 border-t border-slate-700 flex flex-wrap items-center gap-4 text-sm">
                    <a href="{{ route('domains.concepts.index', $domain) }}" class="text-indigo-400 hover:text-indigo-300 transition">Voir tous les concepts</a>
                    <span class="text-slate-600">|</span>
                    <a href="{{ route('concepts.archived') }}" class="text-slate-400 hover:text-slate-300 transition">Concepts archivés</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection