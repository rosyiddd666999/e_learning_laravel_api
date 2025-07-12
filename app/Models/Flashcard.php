<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Flashcard extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'question',
        'answer',
        'difficulty',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function userAttempts(): HasMany
    {
        return $this->hasMany(UserFlashcardAttempt::class);
    }

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeByDifficulty(Builder $query, string $difficulty): Builder
    {
        return $query->where('difficulty', $difficulty);
    }

    // Helper Methods
    public function getSuccessRate(): float
    {
        $totalAttempts = $this->userAttempts()->sum('attempt_count');
        $correctAttempts = $this->userAttempts()->where('is_correct', true)->count();

        return $totalAttempts > 0 ? ($correctAttempts / $totalAttempts) * 100 : 0;
    }
}
