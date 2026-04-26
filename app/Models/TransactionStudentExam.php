<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionStudentExam extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'exam_id',
        'start_time',
        'end_time',
        'is_finished',
        'total_score'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function answers()
    {
        return $this->hasMany(UserAnswer::class);
    }

    public function isFinished()
    {
        return !is_null($this->end_time);
    }
}
