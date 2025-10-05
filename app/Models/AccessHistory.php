<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

/**
 * @property integer $id
 * @property integer $user_id
 * @property string $session_id
 * @property string $client_ip
 * @property string $login_at
 * @property string $logout_at
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 */
class AccessHistory extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'access_histories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'session_id',
        'client_ip',
        'login_at',
        'logout_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get formatted_login_at
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function formattedLoginAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) =>
            Carbon::parse($attributes['login_at'])->format('Y/m/d h:i:s'),
        );
    }

    /**
     * Get formatted_logout_at
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function formattedLogoutAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) =>
            Carbon::parse($attributes['logout_at'])->format('Y/m/d h:i:s'),
        );
    }
}
