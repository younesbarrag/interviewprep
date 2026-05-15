@extends('layouts.app')

@section('title', 'Concepts archives')

@section('content')
<div class="py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="mb-2">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-indigo-400 hover:text-indigo-300 transition text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Retour au dashboard
            </a>
        </div>

        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <h1 class="text-2xl font-bold text-slate-100">Concepts archives</h1>
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

        @if($concepts->isEmpty())
            <div class="bg-slate-800/70 rounded-xl p-8 text-center border border-slate-700">
                <p class="text-slate-400">Aucun concept archive.</p>
            </div>
        @else
            <div class="bg-slate-800/70 rounded-xl border border-slate-700 overflow-hidden">
                <table class="min-w-full">
                    <thead class="bg-slate-700/70">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Titre</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Domaine</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Difficulte</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Archive le</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/50">
                        @foreach($concepts as $concept)
                            <tr class="hover:bg-slate-700/30 transition">
                                <td class="px-4 py-3">
                                    <span class="text-slate-200 font-medium">{{ $concept->title }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $concept->domain->color }}"></span>
                                        <span class="text-slate-400 text-sm">{{ $concept->domain->name }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-500/20 text-blue-400">
                                        {{ $concept->difficultyLabel() }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-500">
                                    {{ $concept->deleted_at->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-3">
                                    <form action="{{ route('concepts.restore', $concept->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white text-xs font-medium px-3 py-1.5 rounded-lg transition">
                                            Restaurer
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection