<?php

namespace App;

use Illuminate\Notifications\Notifiable;
//use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use  HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'nif',
        'photo',
        'created_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'photo'
    ];

    public static function userTypeToStr($type){
        switch ($type){
            case 'u':
                return 'Platform User';
                case 'a':
                    return 'Admin';
                    case 'o':
                        return 'Operator';
        }
        return 'Unknown';
    }

    public function isAdmin(){
        return $this->type === "a";
    }

    public function isOperator(){
        return $this->type === "o";
    }
    public function isPlatform(){
        return $this->type === "u";
    }
    
}


