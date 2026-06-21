<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Traits\LogsActivity;

class User extends Authenticatable
{
    use HasFactory, Notifiable, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'nim',
        'nip',
        'department_id',
        'entry_year',
        'supervisor_id',
        'avatar',
        'is_active',
        'must_change_password',
        'last_login_at',
        'created_by',
        'updated_by',
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
            'is_active' => 'boolean',
            'must_change_password' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    public function hasRole($slug)
    {
        return $this->roles->contains('slug', $slug);
    }

    public function hasAnyRole(array $slugs)
    {
        return $this->roles->whereIn('slug', $slugs)->isNotEmpty();
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function supervisedStudents()
    {
        return $this->hasMany(User::class, 'supervisor_id');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Get the user's legacy jabatan attribute derived from roles.
     *
     * @return string|null
     */
    public function getJabatanAttribute()
    {
        $slugs = $this->roles->pluck('slug')->toArray();

        if (in_array('admin_lab', $slugs)) {
            return 'admin lab';
        }
        if (in_array('assistant', $slugs)) {
            return 'asisten dosen';
        }
        if (in_array('lecturer', $slugs)) {
            return 'dosen';
        }
        if (in_array('student', $slugs)) {
            return 'Mahasiswa';
        }

        return null;
    }
}
