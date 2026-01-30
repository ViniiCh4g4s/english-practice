<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sentence extends Model
{
    use HasFactory;

    protected $fillable = [
        'text_pt',
        'text_en_reference',
        'level',
        'topic',
        'difficulty_score',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'difficulty_score' => 'integer',
    ];

    public function userAnswers(): HasMany
    {
        return $this->hasMany(UserAnswer::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(UserFavorite::class);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeByLevel($query, string $level)
    {
        return $query->where('level', $level);
    }

    public function scopeByTopic($query, string $topic)
    {
        return $query->where('topic', $topic);
    }
}
