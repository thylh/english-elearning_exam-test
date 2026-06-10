<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update user to student
        DB::table('users')->where('role', 'user')->update(['role' => 'student']);
        // Update instructor to teacher
        DB::table('users')->where('role', 'instructor')->update(['role' => 'teacher']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Update teacher to instructor
        DB::table('users')->where('role', 'teacher')->update(['role' => 'instructor']);
        // Update student to user
        DB::table('users')->where('role', 'student')->update(['role' => 'user']);
    }
};
