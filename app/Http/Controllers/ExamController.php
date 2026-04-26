<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransactionStudentExam;
use App\Models\User;
use App\Models\Exam;
use App\Models\AnswerChoice;
use App\Models\TransactionQuestionAnswer;

class ExamController extends Controller
{

    public function getListExam(Request $request)
    {
        $exams = Exam::where('status', 1)->get();
        return response()->json([
            'code' => 200,
            'message' => 'List of exams',
            'data' => $exams
        ]);
    }
    public function startExam(Request $request)
    {
        $request->validate([
            'exam_id' => 'required|exists:exams,id',
        ]);

        $transactionStudentExamExist = TransactionStudentExam::where(
            'user_id', $request->user()->id)
            ->where('exam_id', $request->exam_id)
            ->where('is_finished', 0)
            ->with('user', 'exam.questions.choices')
            ->first();

        if ($transactionStudentExamExist) {
            return response()->json([
                'code' => 200,
                'message' => 'Exam already started',
                'data' => $transactionStudentExamExist
            ]);
        }
        else {
            $transactionStudentExam = TransactionStudentExam::create([
                'user_id' => $request->user()->id,
                'exam_id' => $request->exam_id,
            ]);
            return response()->json([
                'code' => 200,
                'message' => 'Exam started',
                'data' => $transactionStudentExam->load('user', 'exam.questions.choices')
            ]);
        }
    }

    public function finishExam(Request $request)
    {
        $request->validate([
            'transaction_student_exam_id' => 'required|exists:transaction_student_exams,id',
            'answer' => 'required|array',
            'answer.*.question_id' => 'required|exists:questions,id',
            'answer.*.answer_id' => 'nullable|exists:answer_choices,id',
        ]);

        $transactionStudentExam = TransactionStudentExam::with('exam.questions')
            ->find($request->transaction_student_exam_id);

        if (!$transactionStudentExam) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        if ($transactionStudentExam->is_finished) {
            return response()->json([
                'message' => 'Exam already finished'
            ], 400);
        }

        $questions = $transactionStudentExam->exam->questions;
        $answersFromUser = collect($request->answer)->keyBy('question_id');

        $answerChoices = AnswerChoice::whereIn(
            'id',
            collect($request->answer)->pluck('answer_id')->filter()
        )->pluck('is_correct', 'id');

        $correct = 0;
        $wrong = 0;
        $empty = 0;
        $totalScore = 0;

        foreach ($questions as $question) {

            $userAnswer = $answersFromUser->get($question->id);

            if (!$userAnswer || !$userAnswer['answer_id']) {
                $empty++;

                TransactionQuestionAnswer::create([
                    'transaction_student_exam_id' => $request->transaction_student_exam_id,
                    'question_id' => $question->id,
                    'answer_id' => null,
                    'score' => 0,
                ]);

                continue;
            }

            $isCorrect = $answerChoices[$userAnswer['answer_id']] ?? false;

            if ($isCorrect) {
                $correct++;
                $score = $question->score;
            }
            else {
                $wrong++;
                $score = 0;
            }

            $totalScore += $score;

            TransactionQuestionAnswer::create([
                'transaction_student_exam_id' => $request->transaction_student_exam_id,
                'question_id' => $question->id,
                'answer_id' => $userAnswer['answer_id'],
                'score' => $score,
            ]);
        }

        $transactionStudentExam->update([
            'is_finished' => 1,
            'total_score' => $totalScore,
        ]);

        return response()->json([
            'code' => 200,
            'message' => 'Exam finished',
            'data' => [
                'correct_answer' => $correct,
                'wrong_answer' => $wrong,
                'empty_answer' => $empty,
                'total_score' => $totalScore,
            ],
        ]);
    }
}
