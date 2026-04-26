<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'exam_id',
        'question_text',
        'score'
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function choices()
    {
        return $this->hasMany(AnswerChoice::class);
    }

    public function userAnswers()
    {
        return $this->hasMany(UserAnswer::class);
    }
}
