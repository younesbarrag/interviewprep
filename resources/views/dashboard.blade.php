@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-100">Dashboard de progression</h1>
                <p class="text-sm text-slate-500 mt-1">Suivez votre preparation aux entretiens techniques</p>
            </div>
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

        @if($totalDomains === 0)
            <div class="bg-slate-800/70 rounded-xl p-10 text-center border border-slate-700">
                <div class="w-12 h-12 mx-auto mb-4 rounded-full bg-indigo-500/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-slate-100 mb-2">Pret a demarrer ?</h3>
                <p class="text-slate-400 mb-6 max-w-md mx-auto">
                    Creez votre premier domaine pour organiser votre preparation aux entretiens techniques.
                </p>
                <a href="{{ route('domains.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-5 py-2.5 rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Creer mon premier domaine
                </a>
            </div>
        @else

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-slate-800/70 rounded-xl p-4 border border-slate-700">
                    <p class="text-xs text-slate-500 mb-1">Domaines</p>
                    <p class="text-2xl font-bold text-slate-100">{{ $totalDomains }}</p>
                </div>
                <div class="bg-slate-800/70 rounded-xl p-4 border border-slate-700">
                    <p class="text-xs text-slate-500 mb-1">Concepts</p>
                    <p class="text-2xl font-bold text-slate-100">{{ $totalConcepts }}</p>
                </div>
                <div class="bg-slate-800/70 rounded-xl p-4 border border-slate-700">
                    <p class="text-xs text-slate-500 mb-1">Archives</p>
                    <p class="text-2xl font-bold text-slate-100">{{ $archivedConcepts }}</p>
                </div>
                <div class="bg-slate-800/70 rounded-xl p-4 border border-slate-700">
                    <p class="text-xs text-slate-500 mb-1">Generations IA</p>
                    <p class="text-2xl font-bold text-slate-100">{{ $totalGenerations }}</p>
                </div>
            </div>

            @if($totalConcepts > 0)
                <div class="bg-slate-800/70 rounded-xl p-5 border border-slate-700">
                    <div class="flex flex-wrap items-center gap-6 text-sm">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-green-500"></span>
                            <span class="text-slate-400">Maitrise:</span>
                            <span class="font-semibold text-green-400">{{ $masteredConcepts }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-amber-400"></span>
                            <span class="text-slate-400">En cours:</span>
                            <span class="font-semibold text-amber-400">{{ $inProgressConcepts }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-orange-500"></span>
                            <span class="text-slate-400">A revoir:</span>
                            <span class="font-semibold text-orange-400">{{ $toReviewConcepts }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-800/70 rounded-xl p-5 border border-slate-700">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <span class="text-sm text-slate-400">Progression globale</span>
                            <p class="text-2xl font-bold text-indigo-400">{{ $globalProgress }}%</p>
                        </div>
                        @if($bestDomain)
                            <div class="text-right">
                                <p class="text-xs text-slate-500 uppercase tracking-wide">Meilleur</p>
                                <p class="text-sm font-medium text-green-400">{{ $bestDomain->name }}</p>
                                <p class="text-xs text-slate-500">{{ $bestDomain->concepts_count > 0 ? round(($bestDomain->mastered_count / $bestDomain->concepts_count) * 100) : 0 }}%</p>
                            </div>
                        @endif
                        @if($worstDomain && $worstDomain->id !== $bestDomain?->id)
                            <div class="text-right border-l border-slate-700 pl-4">
                                <p class="text-xs text-slate-500 uppercase tracking-wide">A ameliorer</p>
                                <p class="text-sm font-medium text-orange-400">{{ $worstDomain->name }}</p>
                                <p class="text-xs text-slate-500">{{ $worstDomain->concepts_count > 0 ? round(($worstDomain->to_review_count / $worstDomain->concepts_count) * 100) : 0 }}%</p>
                            </div>
                        @endif
                    </div>
                    <div class="w-full h-2.5 bg-slate-700 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full transition-all" style="width: {{ $globalProgress }}%"></div>
                    </div>
                    <div class="flex justify-between mt-2 text-xs text-slate-600">
                        <span>0%</span><span>50%</span><span>100%</span>
                    </div>
                </div>
            @endif

            @if($recentConcepts->count() > 0)
                <div class="bg-slate-800/70 rounded-xl p-5 border border-slate-700">
                    <h3 class="text-sm font-semibold text-slate-100 mb-3">Derniers concepts</h3>
                    <div class="space-y-2">
                        @foreach($recentConcepts as $concept)
                            <a href="{{ route('concepts.show', $concept) }}" class="flex items-center justify-between p-2.5 rounded-lg hover:bg-slate-700/50 transition">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background-color: {{ $concept->domain->color }}"></span>
                                    <span class="text-sm text-slate-200">{{ $concept->title }}</span>
                                </div>
                                <span class="text-xs text-slate-500">{{ $concept->updated_at->diffForHumans() }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($domainStats->count() > 0)
                <div class="bg-slate-800/70 rounded-xl p-5 border border-slate-700">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-slate-100">Progression par domaine</h3>
                        <a href="{{ route('domains.index') }}" class="text-xs text-indigo-400 hover:text-indigo-300 transition">Voir tout</a>
                    </div>
                    <div class="space-y-3">
                        @foreach($domainStats as $domain)
                            @php
                                $pct = $domain->concepts_count > 0 ? round(($domain->mastered_count / $domain->concepts_count) * 100) : 0;
                            @endphp
                            <a href="{{ route('domains.concepts.index', $domain) }}" class="block p-2.5 rounded-lg hover:bg-slate-700/50 transition">
                                <div class="flex items-center justify-between mb-1.5">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $domain->color }}"></span>
                                        <span class="text-sm text-slate-200">{{ $domain->name }}</span>
                                    </div>
                                    <span class="text-xs text-slate-500">{{ $domain->mastered_count }}/{{ $domain->concepts_count }}</span>
                                </div>
                                <div class="w-full h-1.5 bg-slate-700 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full transition-all" style="width: {{ $pct }}%; background-color: {{ $domain->color }}"></div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="grid sm:grid-cols-3 gap-4">
                <a href="{{ route('domains.create') }}" class="flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-5 py-3 rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Nouveau domaine
                </a>
                <a href="{{ route('domains.index') }}" class="flex items-center justify-center gap-2 bg-slate-700 hover:bg-slate-600 text-slate-200 font-semibold px-5 py-3 rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    Voir domaines
                </a>
                <a href="{{ route('concepts.archived') }}" class="flex items-center justify-center gap-2 bg-slate-700 hover:bg-slate-600 text-slate-200 font-semibold px-5 py-3 rounded-lg transition relative">
                    @if($archivedConcepts > 0)
                        <span class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center">{{ $archivedConcepts }}</span>
                    @endif
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                    </svg>
                    Concepts archives
                </a>
            </div>

        @endif
    </div>
</div>
@endsection