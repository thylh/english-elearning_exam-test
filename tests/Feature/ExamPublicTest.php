<?php

namespace Tests\Feature;

use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExamPublicTest extends TestCase
{
    use RefreshDatabase;

    public function test_published_exam_test_page_lists_exams(): void
    {
        Exam::query()->create([
            'title' => 'Full IELTS Exam',
            'slug' => 'full-ielts-exam',
            'type' => 'exam',
            'category' => 'IELTS',
            'band' => '7.0',
            'description' => 'Full exam for testing.',
            'published' => true,
            'user_id' => User::factory()->create()->id,
        ]);

        $response = $this->get('/exam-test');

        $response->assertStatus(200);
        $response->assertSee('Full IELTS Exam');
        $response->assertSee('Làm bài');
    }

    public function test_full_exam_without_skill_can_be_started_using_first_section_skill(): void
    {
        $exam = Exam::query()->create([
            'title' => 'Full IELTS Exam',
            'slug' => 'full-ielts-exam',
            'type' => 'exam',
            'category' => 'IELTS',
            'band' => '7.0',
            'description' => 'Full exam for testing.',
            'published' => true,
            'user_id' => User::factory()->create()->id,
        ]);

        ExamQuestion::query()->create([
            'exam_id' => $exam->id,
            'section_skill' => 'reading',
            'part_number' => 1,
            'question_text' => 'What is the main idea?',
            'question_type' => 'text',
            'options' => null,
            'order' => 1,
        ]);

        $response = $this->get('/exams/' . $exam->id . '/take?origin=exam-test');

        $response->assertStatus(200);
        $response->assertSee('What is the main idea?');
        $response->assertSee('Nộp bài');
    }
}
