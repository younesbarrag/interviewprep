@extends('layouts.app')

@section('title', $concept->title)

@section('content')
<div class="py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-4">
            <a href="{{ route('domains.concepts.index', $concept->domain) }}" class="inline-flex items-center gap-2 text-indigo-400 hover:text-indigo-300 transition text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Retour aux concepts
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 rounded-lg bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 rounded-lg bg-red-500/10 border border-red-500/30 text-red-300 text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-slate-800/70 rounded-xl border border-slate-700 overflow-hidden">
            <div class="p-5 border-b border-slate-700">
                <div class="flex items-start justify-between gap-4 mb-4">
                    <div>
                        <h1 class="text-xl font-bold text-slate-100">{{ $concept->title }}</h1>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="w-2 h-2 rounded-full" style="background-color: {{ $concept->domain->color }}"></span>
                            <span class="text-xs text-slate-500">{{ $concept->domain->name }}</span>
                        </div>
                    </div>
                    <a href="{{ route('concepts.edit', $concept) }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-3 py-1.5 rounded-lg transition">Modifier</a>
                </div>

                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-500/20 text-blue-400">{{ $concept->difficultyLabel() }}</span>
                    <span class="px-2 py-1 text-xs font-medium rounded-full
                        @if($concept->status === 'to_review') bg-orange-500/20 text-orange-400
                        @elseif($concept->status === 'in_progress') bg-amber-500/20 text-amber-400
                        @else bg-green-500/20 text-green-400 @endif">
                        {{ $concept->statusLabel() }}
                    </span>
                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-purple-500/20 text-purple-400">
                        {{ $concept->generated_questions_count ?? 0 }} questions
                    </span>
                </div>

                <form action="{{ route('concepts.generate-questions', $concept) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-5 py-2.5 rounded-lg transition flex items-center gap-2 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Generer des questions d'entretien
                    </button>
                </form>
            </div>

            <div class="p-5 border-b border-slate-700">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-base font-semibold text-slate-100">Questions d'entretien IA</h2>
                    @if($concept->generatedQuestions->count() > 0)
                        <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-purple-500/20 text-purple-400">
                            {{ $concept->generatedQuestions->count() }} gen{{ $concept->generatedQuestions->count() > 1 ? 's' : '' }}
                        </span>
                    @endif
                </div>
                @if($concept->generatedQuestions->count() > 0)
                    <div class="space-y-3">
                        @foreach($concept->generatedQuestions as $index => $generated)
                            <div class="bg-slate-700/50 rounded-lg p-3 border border-slate-700">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs text-slate-500">
                                        #{{ $concept->generatedQuestions->count() - $index }} - {{ $generated->created_at->format('d/m/Y H:i') }}
                                    </span>
                                    <form action="{{ route('generated-questions.destroy', $generated) }}" method="POST" onsubmit="return confirm('Supprimer ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300 text-xs font-medium transition">Supprimer</button>
                                    </form>
                                </div>
                                <ul class="space-y-1.5">
                                    @foreach($generated->questions as $question)
                                        <li class="flex items-start gap-2">
                                            <span class="w-5 h-5 rounded-full bg-purple-500/20 text-purple-400 text-xs font-medium flex items-center justify-center flex-shrink-0 mt-0.5">{{ $loop->iteration }}</span>
                                            <span class="text-slate-300 text-sm">{{ $question }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-slate-700/30 rounded-lg px-4 py-2.5 flex items-center justify-between" style="max-height: 60px;">
                        <span class="text-sm text-slate-400">Aucune question generee.</span>
                        <span class="text-xs text-slate-500">Cliquez sur Generer</span>
                    </div>
                @endif
            </div>

            <div class="p-5 border-b border-slate-700">
                <h2 class="text-sm font-semibold text-slate-100 mb-2">Explication</h2>
                <p class="text-slate-300 text-sm whitespace-pre-wrap leading-relaxed">{{ $concept->explanation }}</p>
            </div>

            <div class="p-5">
                <form action="{{ route('concepts.destroy', $concept) }}" method="POST" class="inline" onsubmit="return confirm('Archiver ce concept ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-medium px-4 py-2 rounded-lg transition">Archiver le concept</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection