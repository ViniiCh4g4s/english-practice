<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'sentence_id',
        'user_text_en',
        'ai_feedback',
        'is_correct',
        'score',
        'attempts',
        'reviewed_at',
    ];

    protected $casts = [
        'ai_feedback' => 'array',
        'is_correct' => 'boolean',
        'score' => 'integer',
        'attempts' => 'integer',
        'reviewed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sentence(): BelongsTo
    {
        return $this->belongsTo(Sentence::class);
    }
}
