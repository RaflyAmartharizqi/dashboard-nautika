<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAnswer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'transaction_student_exam_id',
        'question_id',
        'answer_choice_id',
        'is_correct',
        'score'
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    public function attempt()
    {
        return $this->belongsTo(TransactionStudentExam::class, 'transaction_student_exam_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function choice()
    {
        return $this->belongsTo(AnswerChoice::class, 'answer_choice_id');
    }
}