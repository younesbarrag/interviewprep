<?php

namespace App\Http\Controllers;

use App\Models\Concept;
use App\Models\Domain;
use App\Models\GeneratedQuestion;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $totalDomains = $user->domains()->count();

        $domainIds = $user->domains()->pluck('id');

        $stats = Concept::query()
            ->whereIn('domain_id', $domainIds)
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = 'mastered' THEN 1 ELSE 0 END) as mastered,
                SUM(CASE WHEN status = 'to_review' THEN 1 ELSE 0 END) as to_review,
                SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) as in_progress
            ")
            ->first();

        $totalConcepts = (int) $stats->total;
        $masteredConcepts = (int) $stats->mastered;
        $toReviewConcepts = (int) $stats->to_review;
        $inProgressConcepts = (int) $stats->in_progress;

        $archivedConcepts = Concept::onlyTrashed()
            ->whereIn('domain_id', $domainIds)
            ->count();

        $totalGenerations = GeneratedQuestion::whereIn('concept_id',
            Concept::whereIn('domain_id', $domainIds)->pluck('id')
        )->count();

        $domainStats = $user->domains()
            ->withCount([
                'concepts' => fn($q) => $q->whereNull('deleted_at'),
                'concepts as mastered_count' => fn($q) => $q->where('status', 'mastered')->whereNull('deleted_at'),
                'concepts as to_review_count' => fn($q) => $q->where('status', 'to_review')->whereNull('deleted_at'),
            ])
            ->get();

        $hasData = $totalDomains > 0;

        $globalProgress = $totalConcepts > 0
            ? round(($masteredConcepts / $totalConcepts) * 100)
            : 0;

        $bestDomain = null;
        if ($domainStats->isNotEmpty()) {
            $bestDomain = $domainStats
                ->filter(fn($d) => $d->concepts_count > 0)
                ->sortByDesc(fn($d) => $d->concepts_count > 0
                    ? $d->mastered_count / $d->concepts_count
                    : 0
                )
                ->first();
        }

        $worstDomain = null;
        if ($domainStats->isNotEmpty()) {
            $worstDomain = $domainStats
                ->filter(fn($d) => $d->concepts_count > 0)
                ->sortByDesc(fn($d) => $d->concepts_count > 0
                    ? $d->to_review_count / $d->concepts_count
                    : 0
                )
                ->first();
        }

        $recentConcepts = [];
        if ($hasData) {
            $recentConcepts = Concept::with('domain')
                ->whereIn('domain_id', $domainIds)
                ->orderBy('updated_at', 'desc')
                ->limit(5)
                ->get();
        }

        return view('dashboard', compact(
            'totalDomains',
            'totalConcepts',
            'masteredConcepts',
            'toReviewConcepts',
            'inProgressConcepts',
            'archivedConcepts',
            'totalGenerations',
            'domainStats',
            'hasData',
            'globalProgress',
            'bestDomain',
            'worstDomain',
            'recentConcepts'
        ));
    }
}