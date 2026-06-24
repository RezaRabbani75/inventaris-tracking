<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Refactor users table
        Schema::table('users', function (Blueprint $table) {
            // Drop existing columns that will be moved to profile tables or removed
            if (Schema::hasColumn('users', 'nuptk')) {
                $table->dropColumn('nuptk');
            }
            if (Schema::hasColumn('users', 'nik')) {
                $table->dropColumn('nik');
            }
            if (Schema::hasColumn('users', 'id_technician')) {
                $table->dropColumn('id_technician');
            }
            if (Schema::hasColumn('users', 'date_of_birth')) {
                $table->dropColumn('date_of_birth');
            }
            if (!Schema::hasColumn('users', 'password')) {
                $table->string('password')->nullable()->after('email_verified_at');
            }
            if (!Schema::hasColumn('users', 'remember_token')) {
                $table->rememberToken();
            }

            // Re-add role column as an enum
            $table->enum('role', ['superadmin', 'teacher', 'student', 'technician'])->default('student')->after('password');
        });

        // Create teacher_profiles table
        Schema::create('teacher_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('nuptk', 20)->unique();
            // Kolom email dihapus karena sudah ada di tabel users
            $table->timestamps();
        });

        // Create student_profiles table
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('nik', 20)->unique();
            // Kolom email dihapus karena sudah ada di tabel users
            $table->timestamps();
        });

        // Create teknisi_profiles table
        Schema::create('teknisi_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('id_teknisi', 20)->unique();
            // Kolom email dihapus karena sudah ada di tabel users
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_profiles');
        Schema::dropIfExists('student_profiles');
        Schema::dropIfExists('teknisi_profiles');

        Schema::table('users', function (Blueprint $table) {
            // Revert changes to users table
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
            if (Schema::hasColumn('users', 'password')) {
                $table->dropColumn('password');
            }
            if (Schema::hasColumn('users', 'remember_token')) {
                $table->dropRememberToken();
            }

            // Re-add columns that were dropped
            $table->string('nuptk')->nullable()->after('email_verified_at');
            $table->string('nik')->nullable()->after('nuptk');
            $table->string('id_technician')->nullable()->unique()->after('nik');
            $table->date('date_of_birth')->nullable()->after('id_technician');
        });
    }
};