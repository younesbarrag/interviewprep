<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['title', 'explanation', 'difficulty', 'status', 'domain_id'])]
class Concept extends Model
{
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'deleted_at' => 'datetime',
        ];
    }

    public function domain(): BelongsTo
    {
        return $this->belongsTo(Domain::class);
    }

    public function generatedQuestions(): HasMany
    {
        return $this->hasMany(GeneratedQuestion::class);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'to_review' => 'A revoir',
            'in_progress' => 'En cours',
            'mastered' => 'Maitrise',
            default => $this->status,
        };
    }

    public function difficultyLabel(): string
    {
        return match ($this->difficulty) {
            'junior' => 'Junior',
            'mid' => 'Mid',
            'senior' => 'Senior',
            default => $this->difficulty,
        };
    }
}