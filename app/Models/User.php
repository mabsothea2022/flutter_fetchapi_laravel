<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table='tbl_users';
    protected $guarded=['id'];
    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    // Add new
    const USER_TOKEN="userToken";

    // Defind relationship
    // User has many chat
    public function chats():HasMany{
        return $this->hasMany(ChatModel::class,'created_by');
    }

}
