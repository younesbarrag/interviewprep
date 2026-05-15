@extends('layouts.app')

@section('title', 'Mes Domaines')

@section('content')
<div class="py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-100">Mes Domaines</h1>
                <p class="text-sm text-slate-500 mt-1">Organisez vos concepts par theme</p>
            </div>
            <a href="{{ route('domains.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-5 py-2.5 rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Nouveau domaine
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

        @if($domains->isEmpty())
            <div class="bg-slate-800/70 rounded-xl p-10 text-center border border-slate-700">
                <div class="w-12 h-12 mx-auto mb-4 rounded-full bg-indigo-500/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-slate-100 mb-2">Aucun domaine</h3>
                <p class="text-slate-400 mb-6">Vous n'avez pas encore de domaines.</p>
                <a href="{{ route('domains.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-5 py-2.5 rounded-lg transition">
                    Creer mon premier domaine
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($domains as $domain)
                    <div class="bg-slate-800/70 rounded-xl p-5 border border-slate-700 hover:border-indigo-500/30 transition">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-slate-100">{{ $domain->name }}</h2>
                            <span class="w-4 h-4 rounded-full flex-shrink-0" style="background-color: {{ $domain->color }}"></span>
                        </div>
                        <div class="text-sm text-slate-400 space-y-1.5 mb-4">
                            <div class="flex justify-between">
                                <span>Concepts</span>
                                <span class="font-medium text-slate-200">{{ $domain->concepts_count }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Maîtrisés</span>
                                <span class="font-medium text-green-400">{{ $domain->mastered_count }}</span>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('domains.show', $domain) }}" class="flex-1 text-center bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-3 py-2 rounded-lg transition">Voir</a>
                            <a href="{{ route('domains.edit', $domain) }}" class="flex-1 text-center bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-3 py-2 rounded-lg transition">Modifier</a>
                            <form action="{{ route('domains.destroy', $domain) }}" method="POST" class="flex-1" onsubmit="return confirm('Supprimer ce domaine ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white text-sm font-medium px-3 py-2 rounded-lg transition">Supprimer</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection