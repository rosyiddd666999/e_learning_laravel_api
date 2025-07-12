<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class DailyChallenge extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'challenge_date',
        'is_completed',
        'score',
        'total_questions',
        'correct_answers',
        'questions_data',
    ];

    protected $casts = [
        'challenge_date' => 'date',
        'is_completed' => 'boolean',
        'score' => 'integer',
        'total_questions' => 'integer',
        'correct_answers' => 'integer',
        'questions_data' => 'array',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Helper Methods
    public function complete(int $correctAnswers): void
    {
        $this->correct_answers = $correctAnswers;
        $this->score = ($correctAnswers / $this->total_questions) * 100;
        $this->is_completed = true;
        $this->save();

        // Update user points
        $points = $correctAnswers * 5; // 5 points per correct answer
        $this->user->addPoints($points);
    }

    public static function createTodayChallenge(User $user, array $questions): self
    {
        return self::create([
            'user_id' => $user->id,
            'challenge_date' => Carbon::today(),
            'is_completed' => false,
            'total_questions' => count($questions),
            'questions_data' => $questions,
        ]);
    }
}
