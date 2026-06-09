<?php

namespace Tests\Feature;

use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\Result;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LearningResultTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_learning_results_dashboard(): void
    {
        $response = $this->get(route('learning-results.index'));
        $response->assertRedirect('/login');
    }

    public function test_student_can_access_learning_results_dashboard(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        
        $response = $this->actingAs($student)->get(route('learning-results.index'));
        $response->assertStatus(200);
        $response->assertSee('Kết Quả Học Tập');
    }

    public function test_student_can_view_own_result_review(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        $exam = Exam::create([
            'title' => 'Reading Test 1',
            'type' => 'practice',
            'skill' => 'reading',
            'published' => true,
        ]);
        
        $result = Result::create([
            'user_id' => $student->id,
            'exam_id' => $exam->id,
            'score' => 80.0,
            'correct_answers' => 4,
            'total_questions' => 5,
            'status' => 'graded',
        ]);

        $response = $this->actingAs($student)->get(route('learning-results.show', $result));
        $response->assertStatus(200);
        $response->assertSee('Reading Test 1');
    }

    public function test_student_cannot_view_others_result_review(): void
    {
        $student1 = User::factory()->create(['role' => 'student']);
        $student2 = User::factory()->create(['role' => 'student']);
        
        $exam = Exam::create([
            'title' => 'Reading Test 1',
            'type' => 'practice',
            'skill' => 'reading',
            'published' => true,
        ]);
        
        $result = Result::create([
            'user_id' => $student1->id,
            'exam_id' => $exam->id,
            'score' => 80.0,
            'correct_answers' => 4,
            'total_questions' => 5,
            'status' => 'graded',
        ]);

        $response = $this->actingAs($student2)->get(route('learning-results.show', $result));
        $response->assertStatus(403);
    }

    public function test_submitting_reading_exam_saves_result(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        $exam = Exam::create([
            'title'     => 'Reading Test 1',
            'type'      => 'practice',
            'skill'     => 'reading',
            'published' => true,
        ]);
        $question = ExamQuestion::create([
            'exam_id'       => $exam->id,
            'question_text' => 'Q1',
            'correct_answer' => 'A',
            'order'         => 1,
        ]);

        $response = $this->actingAs($student)->postJson(route('exams.submit', $exam), [
            'answers' => [$question->id => 'A'],
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('results', [
            'user_id' => $student->id,
            'exam_id' => $exam->id,
            'score' => 100.0,
            'status' => 'graded',
        ]);
    }

    public function test_grading_submission_updates_associated_result(): void
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $student    = User::factory()->create(['role' => 'student']);
        
        $exam = Exam::create([
            'title'     => 'Writing Exam',
            'type'      => 'practice',
            'skill'     => 'writing',
            'published' => true,
        ]);
        $question = ExamQuestion::create([
            'exam_id'       => $exam->id,
            'question_text' => 'Write about your favorite sport.',
            'order'         => 1,
        ]);

        $result = Result::create([
            'user_id' => $student->id,
            'exam_id' => $exam->id,
            'score' => null,
            'total_questions' => 1,
            'status' => 'pending',
        ]);

        $submission = Submission::create([
            'exam_id'        => $exam->id,
            'question_id'    => $question->id,
            'user_id'        => $student->id,
            'result_id'      => $result->id,
            'type'           => 'writing',
            'answer_text'    => 'I love basketball.',
            'grading_method' => 'manual',
            'status'         => 'pending',
        ]);

        $response = $this->actingAs($instructor)->patch(
            route('instructor.submissions.update', $submission),
            [
                'manual_score'    => 75.0,
                'manual_feedback' => 'Good job.',
            ]
        );

        $response->assertRedirect();
        
        $this->assertDatabaseHas('results', [
            'id' => $result->id,
            'score' => 75.0,
            'status' => 'graded',
        ]);

        // Verify the submission itself was updated with manual grade
        $this->assertDatabaseHas('submissions', [
            'id'              => $submission->id,
            'status'          => 'graded',
            'manual_score'    => 75.0,
            'manual_feedback' => 'Good job.',
        ]);
    }
}
