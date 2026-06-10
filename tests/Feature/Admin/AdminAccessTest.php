<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $compiledPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'codex-view-cache-admin';
        File::ensureDirectoryExists($compiledPath);
        config(['view.compiled' => $compiledPath]);
    }

    public function test_admin_login_redirects_to_overview_page(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->postJson(route('login'), [
            'login' => $admin->email,
            'password' => 'password',
        ]);

        $response->assertOk();
        $response->assertJsonPath('redirect', route('admin.overview'));
    }

    public function test_admin_can_open_overview_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.overview'));

        $response->assertOk();
        $response->assertSee('Bảng điều khiển');
    }

    public function test_admin_cannot_open_teacher_exam_management(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('teacher.exams.index'));

        $response->assertForbidden();
    }

    public function test_admin_cannot_open_profile_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('profile.edit'));

        $response->assertForbidden();
    }

    public function test_dashboard_redirects_admin_to_overview_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/dashboard');

        $response->assertRedirect(route('admin.overview'));
    }

    public function test_admin_can_export_backup(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.backup.export'));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/json; charset=UTF-8');
        $response->assertHeader('content-disposition');
    }
}
