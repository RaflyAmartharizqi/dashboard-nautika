<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
class TransactionQuestionAnswer extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'transaction_question_answer';
    protected $fillable = [
        'transaction_student_exam_id',
        'question_id',
        'answer_id',
        'score',
    ];
    public function transactionStudentExam()
    {
        return $this->belongsTo(TransactionStudentExam::class);
    }
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
    public function answer()
    {
        return $this->belongsTo(AnswerChoice::class);
    }
}
