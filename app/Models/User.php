<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use UUID;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $guarded = ['id', 'uuid'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        // 'google2FA_secret',
        // 'google2FA_key',
        'sms_code',
        'verification_token',
        'updated_mail',
        'updated_number',
        'code_expired_at',
        'email_verified_at',
    ];
    
    public static function findUser($username)
    {
        return static::where('username', $username)->orWhere('email', $username)->first();
    }

    public function scopeFindEmailToken($query,$email,$token)
    {
        return $query->where('email', $email)->where('verification_token', $token)->first();
    }

    //Relations
    public function provisionAccount()
    {
        return $this->belongsTo(ProvisionAccount::class, 'provision_account_id');
    }
}
