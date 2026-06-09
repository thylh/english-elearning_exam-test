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
        Schema::create('writing_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('exam_questions')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->text('answer_text');
            $table->string('grading_method')->default('manual'); // 'auto' or 'manual'
            $table->decimal('auto_score', 5, 2)->nullable();
            $table->text('auto_feedback')->nullable();
            $table->decimal('manual_score', 5, 2)->nullable();
            $table->text('manual_feedback')->nullable();
            $table->string('status')->default('pending'); // pending, graded
            $table->foreignId('graded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('graded_at')->nullable();
            $table->timestamps();

            $table->index(['exam_id', 'question_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('writing_submissions');
    }
};
