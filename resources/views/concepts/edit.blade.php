@extends('layouts.app')

@section('title', 'Modifier le concept - ' . $concept->title)

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="mb-6">
            <a href="{{ route('concepts.show', $concept) }}" class="text-indigo-600 hover:text-indigo-800">
                ← Retour au concept
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Modifier le concept</h1>

            <form method="POST" action="{{ route('concepts.update', $concept) }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Titre</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $concept->title) }}" required
                        class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white shadow-sm @error('title') border-red-500 @enderror">
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="explanation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Explication</label>
                    <textarea name="explanation" id="explanation" rows="6" required
                        class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white shadow-sm @error('explanation') border-red-500 @enderror">{{ old('explanation', $concept->explanation) }}</textarea>
                    @error('explanation')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="difficulty" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Difficulté</label>
                    <select name="difficulty" id="difficulty" required
                        class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white shadow-sm @error('difficulty') border-red-500 @enderror">
                        <option value="junior" {{ $concept->difficulty === 'junior' ? 'selected' : '' }}>Junior</option>
                        <option value="mid" {{ $concept->difficulty === 'mid' ? 'selected' : '' }}>Mid</option>
                        <option value="senior" {{ $concept->difficulty === 'senior' ? 'selected' : '' }}>Senior</option>
                    </select>
                    @error('difficulty')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Statut</label>
                    <select name="status" id="status" required
                        class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white shadow-sm @error('status') border-red-500 @enderror">
                        <option value="to_review" {{ $concept->status === 'to_review' ? 'selected' : '' }}>A revoir</option>
                        <option value="in_progress" {{ $concept->status === 'in_progress' ? 'selected' : '' }}>En cours</option>
                        <option value="mastered" {{ $concept->status === 'mastered' ? 'selected' : '' }}>Maîtrisé</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('concepts.show', $concept) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2">
                        Annuler
                    </a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection