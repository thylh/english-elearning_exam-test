<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $compiledPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'codex-view-cache-profile';
        File::ensureDirectoryExists($compiledPath);
        config(['view.compiled' => $compiledPath]);
    }

    public function test_student_can_view_profile_page(): void
    {
        $student = User::factory()->create(['role' => 'student']);

        $response = $this->actingAs($student)->get(route('profile.edit'));

        $response->assertOk();
        $response->assertSee('Hồ sơ cá nhân');
    }

    public function test_instructor_can_view_profile_page(): void
    {
        $instructor = User::factory()->create(['role' => 'instructor']);

        $response = $this->actingAs($instructor)->get(route('profile.edit'));

        $response->assertOk();
        $response->assertSee('Hồ sơ cá nhân');
    }

    public function test_user_can_update_profile_information(): void
    {
        $user = User::factory()->create([
            'role' => 'student',
            'name' => 'Old Name',
            'email' => 'old@example.com',
        ]);

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'name' => 'New Name',
            'email' => 'new@example.com',
        ]);

        $response->assertRedirect(route('profile.edit'));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'New Name',
            'email' => 'new@example.com',
        ]);
    }

    public function test_user_can_change_password_with_current_password(): void
    {
        $user = User::factory()->create([
            'role' => 'instructor',
            'password' => Hash::make('old-password'),
        ]);

        $response = $this->actingAs($user)->patch(route('profile.password'), [
            'current_password' => 'old-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response->assertRedirect(route('profile.edit'));

        $this->assertTrue(Hash::check('new-password', $user->fresh()->password));
    }
}
