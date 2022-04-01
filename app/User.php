<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'surname', 'othername', 'email', 'phone_no', 'alternative_phone_no', 'photo', 'nin', 'role_id',
        'branch_id', 'is_doctor', 'password',
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function UserRole()
    {
        return $this->belongsTo('App\Role', 'role_id');
    }

    public function branch()
    {
        return $this->belongsTo('App\Branch', 'branch_id');
    }
}
