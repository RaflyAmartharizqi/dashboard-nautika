<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\AnswerChoice;
class AnswerChoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'question_id',
        'answer_text',
        'is_correct',
        'image'
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
