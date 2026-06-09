<?php

namespace Tests\Feature\Instructor;

use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SubmissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    // ==========================================
    // SPEAKING SUBMISSION TESTS
    // ==========================================

    public function test_student_can_submit_speaking_test_manual_grading(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        $exam = Exam::create([
            'title'     => 'Speaking Practice 1',
            'type'      => 'practice',
            'skill'     => 'speaking',
            'subtype'   => 'single',
            'published' => true,
        ]);
        $question = ExamQuestion::create([
            'exam_id'       => $exam->id,
            'question_text' => 'Describe a beautiful city you visited.',
            'question_type' => 'speaking',
            'order'         => 1,
        ]);

        $base64Audio = 'data:audio/webm;base64,GkXfo69ChoEBQveBAULygQRC84EIQoKEd2VibXRIQoSEhQeBAkCQgQRE84EIQoKEd2VibXRIQoSEhQ=';

        $response = $this->actingAs($student)->postJson(route('exams.submit', $exam), [
            'answers'        => [$question->id => $base64Audio],
            'grading_method' => 'manual',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('submissions', [
            'exam_id'        => $exam->id,
            'question_id'    => $question->id,
            'user_id'        => $student->id,
            'type'           => 'speaking',
            'grading_method' => 'manual',
            'status'         => 'pending',
        ]);

        $submission = Submission::first();
        $this->assertNotNull($submission->audio_path);
        Storage::disk('public')->assertExists($submission->audio_path);
    }

    public function test_student_can_submit_speaking_test_auto_grading(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        $exam = Exam::create([
            'title'     => 'Speaking Practice 2',
            'type'      => 'practice',
            'skill'     => 'speaking',
            'subtype'   => 'single',
            'published' => true,
        ]);
        $question = ExamQuestion::create([
            'exam_id'       => $exam->id,
            'question_text' => 'Describe your favorite movie.',
            'question_type' => 'speaking',
            'order'         => 1,
        ]);

        $base64Audio = 'data:audio/webm;base64,GkXfo69ChoEBQveBAULygQRC84EIQoKEd2VibXRIQoSEhQeBAkCQgQRE84EIQoKEd2VibXRIQoSEhQ=';

        $response = $this->actingAs($student)->postJson(route('exams.submit', $exam), [
            'answers'        => [$question->id => $base64Audio],
            'grading_method' => 'auto',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('submissions', [
            'exam_id'        => $exam->id,
            'question_id'    => $question->id,
            'user_id'        => $student->id,
            'type'           => 'speaking',
            'grading_method' => 'auto',
            'status'         => 'graded',
        ]);

        $submission = Submission::first();
        $this->assertNotNull($submission->auto_score);
        $this->assertNotNull($submission->auto_feedback);
    }

    // ==========================================
    // WRITING SUBMISSION TESTS
    // ==========================================

    public function test_student_can_submit_writing_test_manual_grading(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        $exam = Exam::create([
            'title'     => 'Writing Practice 1',
            'type'      => 'practice',
            'skill'     => 'writing',
            'subtype'   => 'single',
            'published' => true,
        ]);
        $question = ExamQuestion::create([
            'exam_id'       => $exam->id,
            'question_text' => 'Write about climate change.',
            'question_type' => 'writing',
            'order'         => 1,
        ]);

        $response = $this->actingAs($student)->postJson(route('exams.submit', $exam), [
            'answers'        => [$question->id => 'Climate change is one of the most serious threats...'],
            'grading_method' => 'manual',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('submissions', [
            'exam_id'        => $exam->id,
            'question_id'    => $question->id,
            'user_id'        => $student->id,
            'type'           => 'writing',
            'grading_method' => 'manual',
            'status'         => 'pending',
        ]);
    }

    public function test_student_can_submit_writing_test_auto_grading(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        $exam = Exam::create([
            'title'     => 'Writing Practice 2',
            'type'      => 'practice',
            'skill'     => 'writing',
            'subtype'   => 'single',
            'published' => true,
        ]);
        $question = ExamQuestion::create([
            'exam_id'       => $exam->id,
            'question_text' => 'Describe the benefits of technology in education.',
            'question_type' => 'writing',
            'order'         => 1,
        ]);

        $response = $this->actingAs($student)->postJson(route('exams.submit', $exam), [
            'answers'        => [$question->id => str_repeat('Technology has transformed education significantly. ', 20)],
            'grading_method' => 'auto',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('submissions', [
            'exam_id'        => $exam->id,
            'question_id'    => $question->id,
            'user_id'        => $student->id,
            'type'           => 'writing',
            'grading_method' => 'auto',
            'status'         => 'graded',
        ]);

        $submission = Submission::first();
        $this->assertNotNull($submission->auto_score);
        $this->assertNotNull($submission->auto_feedback);
    }

    // ==========================================
    // INSTRUCTOR GRADING TESTS
    // ==========================================

    public function test_instructor_can_list_all_submissions(): void
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $student    = User::factory()->create(['role' => 'student']);
        $exam = Exam::create([
            'title'     => 'Mixed Exam',
            'type'      => 'practice',
            'skill'     => 'writing',
            'subtype'   => 'single',
            'published' => true,
        ]);
        $question = ExamQuestion::create([
            'exam_id'       => $exam->id,
            'question_text' => 'Q1',
            'question_type' => 'writing',
            'order'         => 1,
        ]);

        Submission::create([
            'exam_id'        => $exam->id,
            'question_id'    => $question->id,
            'user_id'        => $student->id,
            'type'           => 'writing',
            'answer_text'    => 'My essay here',
            'grading_method' => 'manual',
            'status'         => 'pending',
        ]);

        $response = $this->actingAs($instructor)->get(route('instructor.submissions.index', ['status' => 'pending']));
        $response->assertStatus(200);
        $response->assertSee('Mixed Exam');
    }

    public function test_instructor_can_grade_writing_submission(): void
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $student    = User::factory()->create(['role' => 'student']);
        $exam = Exam::create([
            'title'     => 'Writing Exam',
            'type'      => 'practice',
            'skill'     => 'writing',
            'subtype'   => 'single',
            'published' => true,
        ]);
        $question = ExamQuestion::create([
            'exam_id'       => $exam->id,
            'question_text' => 'Write about your hometown.',
            'question_type' => 'writing',
            'order'         => 1,
        ]);

        $submission = Submission::create([
            'exam_id'        => $exam->id,
            'question_id'    => $question->id,
            'user_id'        => $student->id,
            'type'           => 'writing',
            'answer_text'    => 'My hometown is a beautiful city...',
            'grading_method' => 'manual',
            'status'         => 'pending',
        ]);

        $response = $this->actingAs($instructor)->patch(
            route('instructor.submissions.update', $submission),
            [
                'manual_score'    => 70.0,
                'manual_feedback' => 'Good structure, improve vocabulary.',
            ]
        );

        $response->assertRedirect();

        $this->assertDatabaseHas('submissions', [
            'id'              => $submission->id,
            'status'          => 'graded',
            'manual_score'    => 70.0,
            'manual_feedback' => 'Good structure, improve vocabulary.',
            'graded_by'       => $instructor->id,
        ]);
    }

    public function test_instructor_can_grade_speaking_submission(): void
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $student    = User::factory()->create(['role' => 'student']);
        $exam = Exam::create([
            'title'     => 'Speaking Exam',
            'type'      => 'practice',
            'skill'     => 'speaking',
            'subtype'   => 'single',
            'published' => true,
        ]);
        $question = ExamQuestion::create([
            'exam_id'       => $exam->id,
            'question_text' => 'Describe your hobby.',
            'question_type' => 'speaking',
            'order'         => 1,
        ]);

        $submission = Submission::create([
            'exam_id'        => $exam->id,
            'question_id'    => $question->id,
            'user_id'        => $student->id,
            'type'           => 'speaking',
            'audio_path'     => 'speaking_submissions/test.webm',
            'grading_method' => 'manual',
            'status'         => 'pending',
        ]);

        $response = $this->actingAs($instructor)->patch(
            route('instructor.submissions.update', $submission),
            [
                'manual_score'    => 80.0,
                'manual_feedback' => 'Good pronunciation, minor grammatical errors.',
            ]
        );

        $response->assertRedirect();

        $this->assertDatabaseHas('submissions', [
            'id'              => $submission->id,
            'status'          => 'graded',
            'manual_score'    => 80.0,
            'manual_feedback' => 'Good pronunciation, minor grammatical errors.',
            'graded_by'       => $instructor->id,
        ]);
    }

    public function test_instructor_can_filter_submissions_by_type(): void
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $student    = User::factory()->create(['role' => 'student']);
        $exam = Exam::create([
            'title'     => 'Filter Test Exam',
            'type'      => 'practice',
            'skill'     => 'writing',
            'subtype'   => 'single',
            'published' => true,
        ]);
        $question = ExamQuestion::create([
            'exam_id'       => $exam->id,
            'question_text' => 'Q',
            'question_type' => 'writing',
            'order'         => 1,
        ]);

        Submission::create([
            'exam_id' => $exam->id, 'question_id' => $question->id, 'user_id' => $student->id,
            'type' => 'writing', 'answer_text' => 'Essay...', 'grading_method' => 'manual', 'status' => 'pending',
        ]);
        Submission::create([
            'exam_id' => $exam->id, 'question_id' => $question->id, 'user_id' => $student->id,
            'type' => 'speaking', 'audio_path' => null, 'grading_method' => 'manual', 'status' => 'pending',
        ]);

        // Filter by writing only
        $response = $this->actingAs($instructor)->get(
            route('instructor.submissions.index', ['status' => 'pending', 'type' => 'writing'])
        );
        $response->assertStatus(200);
        $this->assertEquals(1, $response->original->getData()['submissions']->total());

        // Filter by speaking only
        $response = $this->actingAs($instructor)->get(
            route('instructor.submissions.index', ['status' => 'pending', 'type' => 'speaking'])
        );
        $response->assertStatus(200);
        $this->assertEquals(1, $response->original->getData()['submissions']->total());

        // No filter - all
        $response = $this->actingAs($instructor)->get(
            route('instructor.submissions.index', ['status' => 'pending', 'type' => 'all'])
        );
        $response->assertStatus(200);
        $this->assertEquals(2, $response->original->getData()['submissions']->total());
    }

    public function test_student_cannot_access_instructor_submissions_page(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        $response = $this->actingAs($student)->get(route('instructor.submissions.index'));
        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_instructor_submissions_page(): void
    {
        $response = $this->get(route('instructor.submissions.index'));
        $response->assertRedirect('/login');
    }
}
