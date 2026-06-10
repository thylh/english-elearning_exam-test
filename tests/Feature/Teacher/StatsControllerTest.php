<?php

namespace Tests\Feature\Instructor;

use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\Result;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class StatsControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $compiledPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'codex-view-cache-stats';
        File::ensureDirectoryExists($compiledPath);
        config(['view.compiled' => $compiledPath]);
    }

    public function test_instructor_can_view_stats_dashboard_with_aggregated_data(): void
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $studentOne = User::factory()->create(['role' => 'student']);
        $studentTwo = User::factory()->create(['role' => 'student']);

        $readingExam = Exam::create([
            'title' => 'Reading Test',
            'type' => 'practice',
            'skill' => 'reading',
            'subtype' => 'single',
            'published' => true,
        ]);

        $writingExam = Exam::create([
            'title' => 'Writing Test',
            'type' => 'practice',
            'skill' => 'writing',
            'subtype' => 'single',
            'published' => true,
        ]);

        $readingQuestion = ExamQuestion::create([
            'exam_id' => $readingExam->id,
            'question_text' => 'Question 1',
            'question_type' => 'text',
            'order' => 1,
        ]);

        ExamQuestion::create([
            'exam_id' => $writingExam->id,
            'question_text' => 'Question 2',
            'question_type' => 'writing',
            'order' => 1,
        ]);

        Result::create([
            'user_id' => $studentOne->id,
            'exam_id' => $readingExam->id,
            'score' => 80.0,
            'correct_answers' => 8,
            'total_questions' => 10,
            'status' => 'graded',
        ]);

        Result::create([
            'user_id' => $studentTwo->id,
            'exam_id' => $readingExam->id,
            'score' => 60.0,
            'correct_answers' => 6,
            'total_questions' => 10,
            'status' => 'graded',
        ]);

        Result::create([
            'user_id' => $studentTwo->id,
            'exam_id' => $writingExam->id,
            'score' => null,
            'correct_answers' => null,
            'total_questions' => 1,
            'status' => 'pending',
        ]);

        DB::table('submissions')->insert([
            'exam_id' => $writingExam->id,
            'question_id' => $readingQuestion->id,
            'user_id' => $studentTwo->id,
            'type' => 'writing',
            'answer_text' => 'My essay',
            'grading_method' => 'manual',
            'manual_score' => null,
            'manual_feedback' => null,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($instructor)->get(route('instructor.stats.index'));

        $response->assertOk();
        $response->assertViewHas('totalStudents', 2);
        $response->assertViewHas('totalExams', 2);
        $response->assertViewHas('totalAttempts', 3);
        $response->assertViewHas('pendingManualSubmissions', 1);
        $response->assertViewHas('overallAverageScore', 70.0);
        $response->assertViewHas('topStudents', function ($value) {
            return $value->count() === 2;
        });
        $response->assertViewHas('activityLabels', function ($value) {
            return is_array($value) && count($value) === 30;
        });
    }

    public function test_student_cannot_access_stats_dashboard(): void
    {
        $student = User::factory()->create(['role' => 'student']);

        $response = $this->actingAs($student)->get(route('instructor.stats.index'));

        $response->assertStatus(403);
    }
}
