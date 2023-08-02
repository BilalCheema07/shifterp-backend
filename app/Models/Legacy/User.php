<?php


namespace App\Models\Legacy;


use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class User extends Model implements Authenticatable
{
    use \Illuminate\Auth\Authenticatable;

    protected $fillable = [
        'Username',
        'PasswordEncrypted',
        'PasswordExpires',
        'PasswordUpdated',
        'SuperAdmin'
    ];

    protected $table = "users";
    protected $connection = "facility";

    protected $hidden = ['Password'];

    public function getAuthIdentifier()
    {
        return $this->UserID;
    }

    public function user()
    {

    }

}
