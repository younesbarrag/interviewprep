@extends('layouts.app')

@section('title', $concept->title)

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="mb-6">
            <a href="{{ route('domains.concepts.index', $concept->domain) }}" class="text-indigo-600 hover:text-indigo-800">
                ← Retour aux concepts
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $concept->title }}</h1>
                    <p class="text-sm text-gray-500 mt-1">
                        <span class="w-3 h-3 inline-block rounded-full" style="background-color: {{ $concept->domain->color }}"></span>
                        {{ $concept->domain->name }}
                    </p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('concepts.edit', $concept) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        Modifier
                    </a>
                </div>
            </div>

            <div class="flex flex-wrap gap-4 mb-6">
                <div>
                    <span class="text-sm text-gray-500 dark:text-gray-400">Difficulté</span>
                    <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                        {{ $concept->difficultyLabel() }}
                    </span>
                </div>
                <div>
                    <span class="text-sm text-gray-500 dark:text-gray-400">Statut</span>
                    <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                        @if($concept->status === 'to_review') bg-yellow-100 text-yellow-800
                        @elseif($concept->status === 'in_progress') bg-blue-100 text-blue-800
                        @else bg-green-100 text-green-800 @endif">
                        {{ $concept->statusLabel() }}
                    </span>
                </div>
                <div>
                    <span class="text-sm text-gray-500 dark:text-gray-400">Questions générées</span>
                    <span class="ml-2 text-gray-900 dark:text-white font-medium">
                        {{ $concept->generated_questions_count ?? 0 }}
                    </span>
                </div>
                <form action="{{ route('concepts.generate-questions', $concept) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                        Générer des questions d'entretien
                    </button>
                </form>
            </div>

            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Questions d'entretien IA</h2>
                @if($concept->generatedQuestions->count() > 0)
                    @foreach($concept->generatedQuestions as $index => $generated)
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-4">
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                    Génération #{{ $concept->generatedQuestions->count() - $index }}
                                    - {{ $generated->created_at->format('d/m/Y H:i') }}
                                </span>
                                <form action="{{ route('generated-questions.destroy', $generated) }}" method="POST" onsubmit="return confirm('Supprimer cette génération?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                            <ul class="list-disc list-inside space-y-2">
                                @foreach($generated->questions as $question)
                                    <li class="text-gray-700 dark:text-gray-300">{{ $question }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                @else
                    <p class="text-gray-500 dark:text-gray-400">Aucune question générée. Cliquez sur le bouton ci-dessus pour générer des questions d'entretien.</p>
                @endif
            </div>

            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Explication</h2>
                <div class="prose dark:prose-invert max-w-none">
                    <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $concept->explanation }}</p>
                </div>
            </div>

            <div class="border-t dark:border-gray-700 pt-4 mt-4">
                <form action="{{ route('concepts.destroy', $concept) }}" method="POST" class="inline" onsubmit="return confirm('Archiver ce concept?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-800">
                        Archiver le concept
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection