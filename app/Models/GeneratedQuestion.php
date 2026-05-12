<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['concept_id', 'questions'])]
class GeneratedQuestion extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'questions' => 'array',
        ];
    }

    public function concept(): BelongsTo
    {
        return $this->belongsTo(Concept::class);
    }
}