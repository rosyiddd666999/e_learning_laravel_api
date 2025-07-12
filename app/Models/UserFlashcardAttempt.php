<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserFlashcardAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'flashcard_id',
        'is_correct',
        'attempt_count',
        'last_attempt',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'attempt_count' => 'integer',
        'last_attempt' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function flashcard(): BelongsTo
    {
        return $this->belongsTo(Flashcard::class);
    }

    // Helper Methods
    public function recordAttempt(bool $isCorrect): void
    {
        $this->increment('attempt_count');
        $this->is_correct = $isCorrect;
        $this->last_attempt = now();
        $this->save();
    }
}
