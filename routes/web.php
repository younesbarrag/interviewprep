<?php

use App\Http\Controllers\AiQuestionController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('/concepts/{concept}/generate-questions', [AiQuestionController::class, 'generate'])
        ->name('concepts.generate-questions');
    Route::delete('/generated-questions/{generatedQuestion}', [AiQuestionController::class, 'destroy'])
        ->name('generated-questions.destroy');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('domains', DomainController::class);

    Route::get('/concepts/archived', [App\Http\Controllers\ConceptController::class, 'archived'])->name('concepts.archived');
    Route::post('/concepts/{concept}/restore', [App\Http\Controllers\ConceptController::class, 'restore'])->name('concepts.restore');

    Route::patch('/concepts/{concept}/status', [App\Http\Controllers\ConceptController::class, 'updateStatus'])->name('concepts.updateStatus');
    Route::resource('concepts', App\Http\Controllers\ConceptController::class);

    Route::get('/domains/{domain}/concepts', [App\Http\Controllers\ConceptController::class, 'index'])->name('domains.concepts.index');
    Route::get('/domains/{domain}/concepts/create', [App\Http\Controllers\ConceptController::class, 'create'])->name('domains.concepts.create');
    Route::post('/domains/{domain}/concepts', [App\Http\Controllers\ConceptController::class, 'store'])->name('domains.concepts.store');
});

require __DIR__.'/auth.php';
