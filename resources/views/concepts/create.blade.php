@extends('layouts.app')

@section('title', 'Nouveau concept - ' . $domain->name)

@section('content')
<div class="py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <a href="{{ route('domains.concepts.index', $domain) }}" class="inline-flex items-center gap-2 text-indigo-400 hover:text-indigo-300 transition text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Retour aux concepts
            </a>
        </div>

        <div class="bg-slate-800/70 rounded-xl p-6 border border-slate-700">
            <div class="flex items-center gap-2 mb-5">
                <span class="w-3 h-3 rounded-full" style="background-color: {{ $domain->color }}"></span>
                <span class="text-sm text-slate-400">{{ $domain->name }}</span>
            </div>
            <h1 class="text-xl font-bold text-slate-100 mb-6">Nouveau concept</h1>

            <form method="POST" action="{{ route('domains.concepts.store', $domain) }}">
                @csrf

                <div class="mb-5">
                    <label for="title" class="block text-sm font-medium text-slate-300 mb-2">Titre</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required
                        class="w-full rounded-lg border border-slate-600 bg-slate-700 text-slate-100 px-4 py-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @error('title') border-red-500 @enderror">
                    @error('title')
                        <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="explanation" class="block text-sm font-medium text-slate-300 mb-2">Explication</label>
                    <textarea name="explanation" id="explanation" rows="5" required
                        class="w-full rounded-lg border border-slate-600 bg-slate-700 text-slate-100 px-4 py-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @error('explanation') border-red-500 @enderror">{{ old('explanation') }}</textarea>
                    @error('explanation')
                        <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="difficulty" class="block text-sm font-medium text-slate-300 mb-2">Difficulte</label>
                    <select name="difficulty" id="difficulty" required
                        class="w-full rounded-lg border border-slate-600 bg-slate-700 text-slate-100 px-4 py-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @error('difficulty') border-red-500 @enderror">
                        <option value="">Selectionner</option>
                        <option value="junior" {{ old('difficulty') === 'junior' ? 'selected' : '' }}>Junior</option>
                        <option value="mid" {{ old('difficulty') === 'mid' ? 'selected' : '' }}>Mid</option>
                        <option value="senior" {{ old('difficulty') === 'senior' ? 'selected' : '' }}>Senior</option>
                    </select>
                    @error('difficulty')
                        <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="status" class="block text-sm font-medium text-slate-300 mb-2">Statut</label>
                    <select name="status" id="status"
                        class="w-full rounded-lg border border-slate-600 bg-slate-700 text-slate-100 px-4 py-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @error('status') border-red-500 @enderror">
                        <option value="to_review" {{ old('status', 'to_review') === 'to_review' ? 'selected' : '' }}>A revoir</option>
                        <option value="in_progress" {{ old('status') === 'in_progress' ? 'selected' : '' }}>En cours</option>
                        <option value="mastered" {{ old('status') === 'mastered' ? 'selected' : '' }}>Maitrise</option>
                    </select>
                    <p class="mt-1.5 text-xs text-slate-500">Par defaut : A revoir</p>
                    @error('status')
                        <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('domains.concepts.index', $domain) }}" class="bg-slate-700 hover:bg-slate-600 text-slate-200 font-medium px-5 py-2.5 rounded-lg transition">Annuler</a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-5 py-2.5 rounded-lg transition">Creer le concept</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection