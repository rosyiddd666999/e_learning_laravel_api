<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StreakHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'activity_date',
        'points_earned',
        'cards_completed',
    ];

    protected $casts = [
        'activity_date' => 'date',
        'points_earned' => 'integer',
        'cards_completed' => 'integer',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Helper Methods
    public static function recordActivity(User $user, int $pointsEarned, int $cardsCompleted): void
    {
        self::updateOrCreate([
            'user_id' => $user->id,
            'activity_date' => now()->toDateString(),
        ], [
            'points_earned' => $pointsEarned,
            'cards_completed' => $cardsCompleted,
        ]);
    }
}
