<?php

namespace Tests\Feature\Instructor;

use App\Models\Exam;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExamClassificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_classify_infers_practice_when_type_is_missing_but_practice_fields_are_present(): void
    {
        $user = User::factory()->create(['role' => 'instructor']);
        $exam = Exam::query()->create([
            'title' => 'Mock Exam',
            'slug' => 'mock-exam',
            'type' => 'exam',
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->patchJson(route('instructor.exams.classify', $exam), [
            'subtype' => 'single',
            'skill' => 'reading',
            'category' => 'IELTS',
            'band' => '5.5',
        ]);

        $response->assertOk()->assertJsonPath('success', true);

        $exam->refresh();

        $this->assertSame('practice', $exam->type);
        $this->assertSame('single', $exam->subtype);
        $this->assertSame('reading', $exam->skill);
        $this->assertSame('IELTS', $exam->category);
        $this->assertSame('5.5', $exam->band);
    }

    public function test_classify_clears_practice_fields_for_exam_type(): void
    {
        $user = User::factory()->create(['role' => 'instructor']);
        $exam = Exam::query()->create([
            'title' => 'Mock Exam 2',
            'slug' => 'mock-exam-2',
            'type' => 'practice',
            'subtype' => 'single',
            'skill' => 'reading',
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->patchJson(route('instructor.exams.classify', $exam), [
            'type' => 'exam',
            'subtype' => 'single',
            'skill' => 'reading',
            'category' => 'Academic',
            'band' => '7.0',
        ]);

        $response->assertOk()->assertJsonPath('success', true);

        $exam->refresh();

        $this->assertSame('exam', $exam->type);
        $this->assertNull($exam->subtype);
        $this->assertNull($exam->skill);
        $this->assertSame('Academic', $exam->category);
        $this->assertSame('7.0', $exam->band);
    }
}
