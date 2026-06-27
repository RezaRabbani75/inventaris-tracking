<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Permission\Traits\HasRoles;

/**
 * User Model
 *
 * This model represents the users in the application.
 * It extends Laravel's Authenticatable class to provide authentication functionality.
 *
 * Traits used:
 * - HasFactory: Enables model factories for testing and seeding
 * - Notifiable: Allows the model to send notifications
 * - HasRoles: Spatie permission trait to manage user roles
 */
class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * These attributes can be filled using mass assignment methods like create() or fill().
     * Only add attributes here that are safe to be mass-assigned.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password', 
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed', 
        ];
    }

    /**
     * Get the student profile associated with the user.
     */
    public function studentProfile(): HasOne
    {
        return $this->hasOne(StudentProfile::class);
    }

    /**
     * Get the teacher profile associated with the user.
     */
    public function teacherProfile(): HasOne
    {
        return $this->hasOne(TeacherProfile::class);
    }

    /**
     * Get the technician profile associated with the user.
     */
    public function teknisiProfile(): HasOne
    {
        return $this->hasOne(TeknisiProfile::class);
    }

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class);
    }

    public function laporanKerusakans()
    {
        return $this->hasMany(LaporanKerusakan::class);
    }
}