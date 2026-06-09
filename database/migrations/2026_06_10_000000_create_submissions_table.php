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
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('exam_questions')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type'); // 'writing' or 'speaking'
            $table->text('answer_text')->nullable(); // writing answer or speaking transcription
            $table->string('audio_path')->nullable(); // speaking audio file
            $table->string('grading_method')->default('manual'); // 'auto' or 'manual'
            $table->decimal('auto_score', 5, 2)->nullable();
            $table->text('auto_feedback')->nullable();
            $table->decimal('manual_score', 5, 2)->nullable();
            $table->text('manual_feedback')->nullable();
            $table->string('status')->default('pending'); // 'pending' or 'graded'
            $table->foreignId('graded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('graded_at')->nullable();
            $table->timestamps();

            $table->index(['exam_id', 'question_id']);
            $table->index(['type', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
