@extends('layouts.app')

@section('title', 'Modifier le concept - ' . $concept->title)

@section('content')
<div class="py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <a href="{{ route('concepts.show', $concept) }}" class="inline-flex items-center gap-2 text-indigo-400 hover:text-indigo-300 transition text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Retour au concept
            </a>
        </div>

        <div class="bg-slate-800/70 rounded-xl p-6 border border-slate-700">
            <h1 class="text-xl font-bold text-slate-100 mb-6">Modifier le concept</h1>

            <form method="POST" action="{{ route('concepts.update', $concept) }}">
                @csrf
                @method('PUT')

                <div class="mb-5">
                    <label for="title" class="block text-sm font-medium text-slate-300 mb-2">Titre</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $concept->title) }}" required
                        class="w-full rounded-lg border border-slate-600 bg-slate-700 text-slate-100 px-4 py-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @error('title') border-red-500 @enderror">
                    @error('title')
                        <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="explanation" class="block text-sm font-medium text-slate-300 mb-2">Explication</label>
                    <textarea name="explanation" id="explanation" rows="5" required
                        class="w-full rounded-lg border border-slate-600 bg-slate-700 text-slate-100 px-4 py-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @error('explanation') border-red-500 @enderror">{{ old('explanation', $concept->explanation) }}</textarea>
                    @error('explanation')
                        <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="difficulty" class="block text-sm font-medium text-slate-300 mb-2">Difficulte</label>
                    <select name="difficulty" id="difficulty" required
                        class="w-full rounded-lg border border-slate-600 bg-slate-700 text-slate-100 px-4 py-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @error('difficulty') border-red-500 @enderror">
                        <option value="junior" {{ $concept->difficulty === 'junior' ? 'selected' : '' }}>Junior</option>
                        <option value="mid" {{ $concept->difficulty === 'mid' ? 'selected' : '' }}>Mid</option>
                        <option value="senior" {{ $concept->difficulty === 'senior' ? 'selected' : '' }}>Senior</option>
                    </select>
                    @error('difficulty')
                        <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="status" class="block text-sm font-medium text-slate-300 mb-2">Statut</label>
                    <select name="status" id="status" required
                        class="w-full rounded-lg border border-slate-600 bg-slate-700 text-slate-100 px-4 py-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @error('status') border-red-500 @enderror">
                        <option value="to_review" {{ $concept->status === 'to_review' ? 'selected' : '' }}>A revoir</option>
                        <option value="in_progress" {{ $concept->status === 'in_progress' ? 'selected' : '' }}>En cours</option>
                        <option value="mastered" {{ $concept->status === 'mastered' ? 'selected' : '' }}>Maitrise</option>
                    </select>
                    @error('status')
                        <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('concepts.show', $concept) }}" class="bg-slate-700 hover:bg-slate-600 text-slate-200 font-medium px-5 py-2.5 rounded-lg transition">Annuler</a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-5 py-2.5 rounded-lg transition">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection