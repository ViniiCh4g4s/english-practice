<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProgress extends Model
{
    use HasFactory;

    protected $table = 'user_progress';

    protected $fillable = [
        'user_id',
        'session_id',
        'total_exercises',
        'correct_exercises',
        'current_streak',
        'best_streak',
        'total_xp',
        'level',
        'last_practice_date',
    ];

    protected $casts = [
        'total_exercises' => 'integer',
        'correct_exercises' => 'integer',
        'current_streak' => 'integer',
        'best_streak' => 'integer',
        'total_xp' => 'integer',
        'level' => 'integer',
        'last_practice_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getAccuracyAttribute(): float
    {
        if ($this->total_exercises === 0) {
            return 0;
        }
        return round(($this->correct_exercises / $this->total_exercises) * 100, 2);
    }
}
