<?php

namespace Tests\Feature;

use App\Models\Exam;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PracticePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_published_practice_exams_appear_on_practice_page(): void
    {
        Exam::query()->create([
            'title' => 'Reading practice demo',
            'slug' => 'reading-practice-demo',
            'type' => 'practice',
            'subtype' => 'single',
            'skill' => 'reading',
            'category' => 'IELTS',
            'band' => '6.5',
            'description' => 'Practice reading content.',
            'published' => true,
            'user_id' => User::factory()->create()->id,
        ]);

        $response = $this->get('/practice');

        $response->assertStatus(200);
        $response->assertSee('Reading practice demo');
        $response->assertSee('Band 6.5');
    }

    public function test_published_practice_without_explicit_type_still_uses_default_and_is_shown(): void
    {
        Exam::query()->create([
            'title' => 'Default practice listening demo',
            'slug' => 'default-practice-listening-demo',
            'subtype' => 'single',
            'skill' => 'listening',
            'category' => 'IELTS',
            'band' => '5.0',
            'description' => 'Default practice listening exam.',
            'published' => true,
            'user_id' => User::factory()->create()->id,
        ]);

        $response = $this->get('/practice');

        $response->assertStatus(200);
        $response->assertSee('Default practice listening demo');
        $response->assertSee('Band 5.0');
    }
}

