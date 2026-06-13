<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $compiledPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'codex-view-cache-auth';
        File::ensureDirectoryExists($compiledPath);
        config(['view.compiled' => $compiledPath]);
    }

    public function test_student_login_redirects_to_dashboard(): void
    {
        $student = User::factory()->create([
            'role' => 'student',
            'email' => 'student@example.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->postJson(route('login'), [
            'login' => $student->email,
            'password' => 'password',
        ]);

        $response->assertOk();
        $response->assertJsonPath('redirect', route('dashboard'));
    }
}
