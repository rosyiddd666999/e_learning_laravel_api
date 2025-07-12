<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'completed_cards',
        'total_points',
        'correct_answers',
        'wrong_answers',
        'accuracy_percentage',
    ];

    protected $casts = [
        'completed_cards' => 'integer',
        'total_points' => 'integer',
        'correct_answers' => 'integer',
        'wrong_answers' => 'integer',
        'accuracy_percentage' => 'float',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Helper Methods
    public function updateAccuracy(): void
    {
        $totalAnswers = $this->correct_answers + $this->wrong_answers;

        if ($totalAnswers > 0) {
            $this->accuracy_percentage = ($this->correct_answers / $totalAnswers) * 100;
            $this->save();
        }
    }

    public function addCorrectAnswer(int $points = 10): void
    {
        $this->increment('correct_answers');
        $this->increment('total_points', $points);
        $this->increment('completed_cards');
        $this->updateAccuracy();
    }

    public function addWrongAnswer(): void
    {
        $this->increment('wrong_answers');
        $this->updateAccuracy();
    }
}
