<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Role;
use App\Enums\UserStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property integer $id
 * @property string $username
 * @property string $name
 * @property string $email
 * @property string $email_verified_at
 * @property string $password
 * @property boolean $role
 * @property boolean $status
 * @property string $remember_token
 * @property string $created_at
 * @property string $updated_at
 */
class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'role',
        'status'
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get text of role
     *
     * @return string
     */
    public function getRoleNameAttribute()
    {
        return Role::from($this->role)->label();
    }

    /**
     * Get text of status
     *
     * @return string
     */
    public function getStatusNameAttribute()
    {
        return UserStatus::from($this->status)->label();
    }

    /**
     * Check user is admin
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role == Role::ADMIN();
    }
}
