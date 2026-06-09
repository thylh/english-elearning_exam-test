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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('exam_id')->constrained()->cascadeOnDelete();
            $table->decimal('score', 5, 2)->nullable();
            $table->integer('correct_answers')->nullable();
            $table->integer('total_questions');
            $table->string('status')->default('pending'); // 'pending', 'graded'
            $table->timestamps();
        });

        Schema::table('submissions', function (Blueprint $table) {
            $table->foreignId('result_id')->nullable()->after('user_id')->constrained('results')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropForeign(['result_id']);
            $table->dropColumn('result_id');
        });

        Schema::dropIfExists('results');
    }
};
