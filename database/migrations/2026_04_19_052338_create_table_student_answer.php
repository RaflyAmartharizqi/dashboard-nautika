<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_student_exam_id')->constrained('transaction_student_exams')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('questions')->cascadeOnDelete();
            $table->foreignId('answer_choice_id')->constrained('answer_choices')->cascadeOnDelete();
            $table->boolean('is_correct')->nullable();
            $table->integer('score')->default(0);
            $table->softDeletes();
            $table->timestamps();
            $table->unique(['transaction_student_exam_id', 'question_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_answers', function (Blueprint $table) {
            $table->dropForeign(['transaction_student_exam_id']);
            $table->dropForeign(['question_id']);
            $table->dropForeign(['answer_choice_id']);
            $table->dropUnique(['transaction_student_exam_id', 'question_id']);
        });

        Schema::dropIfExists('user_answers');
    }
};
