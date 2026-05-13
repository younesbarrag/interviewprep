@extends('layouts.app')

@section('title', $domain->name)

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="mb-6">
            <a href="{{ route('domains.index') }}" class="text-indigo-600 hover:text-indigo-800">
                ← Retour aux domaines
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <span class="w-6 h-6 rounded-full" style="background-color: {{ $domain->color }}"></span>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $domain->name }}</h1>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('domains.edit', $domain) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        Modifier
                    </a>
                    <form action="{{ route('domains.destroy', $domain) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce domaine ? Cette action supprimera aussi tous les concepts associés.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Supprimer
                        </button>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-gray-50 dark:bg-gray-700 rounded p-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total concepts</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $domain->concepts_count }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 rounded p-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Concepts maîtrisés</p>
                    <p class="text-2xl font-bold text-green-600">{{ $domain->mastered_count }}</p>
                </div>
            </div>

            <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Concepts</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('domains.concepts.create', $domain) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                            Nouveau concept
                        </a>
                    </div>
                </div>

                @if($concepts->isEmpty())
                    <div class="text-center py-8">
                        <p class="text-gray-500 dark:text-gray-400 mb-4">Aucun concept pour le moment.</p>
                        <a href="{{ route('domains.concepts.create', $domain) }}" class="text-indigo-600 hover:text-indigo-800">
                            Créer votre premier concept
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Titre</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Difficulté</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Statut</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($concepts as $concept)
                                    <tr>
                                        <td class="px-4 py-3">
                                            <a href="{{ route('concepts.show', $concept) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                                                {{ $concept->title }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ $concept->difficultyLabel() }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @if($concept->status === 'to_review') bg-yellow-100 text-yellow-800
                                                @elseif($concept->status === 'in_progress') bg-blue-100 text-blue-800
                                                @else bg-green-100 text-green-800 @endif">
                                                {{ $concept->statusLabel() }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            <a href="{{ route('concepts.show', $concept) }}" class="text-indigo-600 hover:text-indigo-800 mr-2">Voir</a>
                                            <a href="{{ route('concepts.edit', $concept) }}" class="text-gray-600 hover:text-gray-800 mr-2">Modifier</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('domains.concepts.index', $domain) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">
                        Voir tous les concepts →
                    </a>
                    <span class="mx-2 text-gray-400">|</span>
                    <a href="{{ route('concepts.archived') }}" class="text-gray-600 hover:text-gray-800 text-sm">
                        Concepts archivés
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection