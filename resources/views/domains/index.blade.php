@extends('layouts.app')

@section('title', 'Mes Domaines')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Mes Domaines</h1>
            <a href="{{ route('domains.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                Nouveau domaine
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if($domains->isEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center">
                <p class="text-gray-500 dark:text-gray-400">Aucun domaine pour le moment.</p>
                <a href="{{ route('domains.create') }}" class="text-indigo-600 hover:text-indigo-800 mt-2 inline-block">
                    Créer votre premier domaine
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($domains as $domain)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $domain->name }}</h2>
                            <span class="w-4 h-4 rounded-full" style="background-color: {{ $domain->color }}"></span>
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                            <p>Total concepts: <span class="font-medium">{{ $domain->concepts_count }}</span></p>
                            <p>Maîtrisés: <span class="font-medium text-green-600">{{ $domain->mastered_count }}</span></p>
                        </div>
                        <div class="mt-4 flex gap-2">
                            <a href="{{ route('domains.show', $domain) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">Voir</a>
                            <a href="{{ route('domains.edit', $domain) }}" class="text-gray-600 hover:text-gray-800 text-sm">Modifier</a>
                            <form action="{{ route('domains.destroy', $domain) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce domaine ? Cette action supprimera aussi tous les concepts associés.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Supprimer</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection