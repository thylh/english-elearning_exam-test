<?php

namespace Tests\Feature\Instructor;

use App\Models\Exam;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExamQuestionControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_practice_single_questions_are_forced_to_part_one_and_exam_skill(): void
    {
        $user = User::factory()->create(['role' => 'instructor']);
        $exam = Exam::query()->create([
            'title' => 'Reading Practice',
            'slug' => 'reading-practice',
            'type' => 'practice',
            'subtype' => 'single',
            'skill' => 'reading',
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->post(route('instructor.exams.questions.store', $exam), [
            'question_text' => 'What is the main idea?',
            'question_type' => 'multiple_choice',
            'options_text' => "A\nB\nC\nD",
            'correct_answer' => 'A',
            'explanation' => 'Sample explanation',
        ]);

        $response->assertRedirect(route('instructor.exams.questions.index', $exam));

        $this->assertDatabaseHas('exam_questions', [
            'exam_id' => $exam->id,
            'section_skill' => 'reading',
            'part_number' => 1,
            'question_text' => 'What is the main idea?',
        ]);
    }
}
