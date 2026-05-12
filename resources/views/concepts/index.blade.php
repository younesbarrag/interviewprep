@extends('layouts.app')

@section('title', 'Concepts - ' . $domain->name)

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="mb-6">
            <a href="{{ route('domains.show', $domain) }}" class="text-indigo-600 hover:text-indigo-800">
                ← Retour au domaine
            </a>
        </div>

        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Concepts</h1>
                <p class="text-sm text-gray-500 mt-1">
                    <span class="w-3 h-3 inline-block rounded-full" style="background-color: {{ $domain->color }}"></span>
                    {{ $domain->name }}
                </p>
            </div>
            <a href="{{ route('domains.concepts.create', $domain) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                Nouveau concept
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6 p-4">
            <form method="GET" class="flex flex-wrap gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Statut</label>
                    <select name="status" class="rounded-md border-gray-300 dark:bg-gray-700 dark:text-white shadow-sm">
                        <option value="">Tous</option>
                        <option value="to_review" {{ request('status') === 'to_review' ? 'selected' : '' }}>A revoir</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>En cours</option>
                        <option value="mastered" {{ request('status') === 'mastered' ? 'selected' : '' }}>Maitrise</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Difficulte</label>
                    <select name="difficulty" class="rounded-md border-gray-300 dark:bg-gray-700 dark:text-white shadow-sm">
                        <option value="">Toutes</option>
                        <option value="junior" {{ request('difficulty') === 'junior' ? 'selected' : '' }}>Junior</option>
                        <option value="mid" {{ request('difficulty') === 'mid' ? 'selected' : '' }}>Mid</option>
                        <option value="senior" {{ request('difficulty') === 'senior' ? 'selected' : '' }}>Senior</option>
                    </select>
                </div>
                <button type="submit" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    Filtrer
                </button>
                @if(request('status') || request('difficulty'))
                    <a href="{{ route('domains.concepts.index', $domain) }}" class="text-indigo-600 hover:text-indigo-800 py-2">
                        Effacer
                    </a>
                @endif
            </form>
        </div>

        @if($concepts->isEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center">
                <p class="text-gray-500 dark:text-gray-400">Aucun concept pour le moment.</p>
                <a href="{{ route('domains.concepts.create', $domain) }}" class="text-indigo-600 hover:text-indigo-800 mt-2 inline-block">
                    Creer votre premier concept
                </a>
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Titre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Difficulte</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($concepts as $concept)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('concepts.show', $concept) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                                        {{ $concept->title }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $concept->difficultyLabel() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <form action="{{ route('concepts.updateStatus', $concept) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" onchange="this.form.submit()" class="text-xs rounded border-gray-300 dark:bg-gray-700 dark:text-white">
                                            <option value="to_review" {{ $concept->status === 'to_review' ? 'selected' : '' }}>A revoir</option>
                                            <option value="in_progress" {{ $concept->status === 'in_progress' ? 'selected' : '' }}>En cours</option>
                                            <option value="mastered" {{ $concept->status === 'mastered' ? 'selected' : '' }}>Maitrise</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ route('concepts.show', $concept) }}" class="text-indigo-600 hover:text-indigo-800 mr-3">Voir</a>
                                    <a href="{{ route('concepts.edit', $concept) }}" class="text-gray-600 hover:text-gray-800 mr-3">Modifier</a>
                                    <form action="{{ route('concepts.destroy', $concept) }}" method="POST" class="inline" onsubmit="return confirm('Archiver ce concept?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">Archiver</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <div class="mt-6">
            <a href="{{ route('concepts.archived') }}" class="text-gray-600 hover:text-gray-800">
                Voir les concepts archives
            </a>
        </div>
    </div>
</div>
@endsection