<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'total_points',
        'current_streak',
        'last_activity_date',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_activity_date' => 'date',
            'total_points' => 'integer',
            'current_streak' => 'integer',
        ];
    }

    // Relationships
    public function userProgress(): HasMany
    {
        return $this->hasMany(UserProgress::class);
    }

    public function dailyChallenges(): HasMany
    {
        return $this->hasMany(DailyChallenge::class);
    }

    public function flashcardAttempts(): HasMany
    {
        return $this->hasMany(UserFlashcardAttempt::class);
    }

    public function streakHistory(): HasMany
    {
        return $this->hasMany(StreakHistory::class);
    }

    // Helper Methods
    public function updateStreak(): void
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        if ($this->last_activity_date?->isToday()) {
            return; // Already updated today
        }

        if ($this->last_activity_date?->isYesterday()) {
            $this->increment('current_streak');
        } else {
            $this->current_streak = 1;
        }

        $this->last_activity_date = $today;
        $this->save();
    }

    public function addPoints(int $points): void
    {
        $this->increment('total_points', $points);
    }

    public function getTodayChallenge()
    {
        return $this->dailyChallenges()
            ->where('challenge_date', Carbon::today())
            ->first();
    }
}
