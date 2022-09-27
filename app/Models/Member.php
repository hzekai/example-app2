<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject; // 引入接口


class Member extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $hidden = [
        'password',
        'two_facor',
        'fcm_token',
    ];

    protected $fillable = [
        'last_active_at',
        'type',
         'phone',
         'password',
         'email',
         'nick_name',
         'account'
    ];

    protected $casts = [
        'account' => 'string',
        'nick_name' => 'string',
        'phone' => 'string',
        'email' => 'string',
        'level' => 'integer',
		'phone' => 'string',
		'language' => 'string',
		'created_at' => 'datetime',
        'updated_at' => 'datetime',
		'bank_name' => 'string',
		'bank_account' => 'string',
		'status' => 'integer',
		'point' => 'integer',
        'f_name' => 'string',
        'l_name' => 'string',
        'is_phone_verified' => 'integer',
        'is_email_verified' => 'integer',
        'last_active_at' => 'datetime',
        'eth_address' => 'string',
        'eth_transfer_key' => 'string',
        'trx_address' => 'string',
        'trx_transfer_key' => 'string'
    ];

    public function scopeNormal($query)
    {
        return $query->where('status', '=', 0);
    }

    public function scopeForbidden($query)
    {
        return $query->where('status', '=', 1);
    }

    public function scopeExit($query)
    {
        return $query->where('status', '=', 2);
    }

    public function emoney()
    {
        return $this->hasOne(EMoney::class, 'user_id', 'id');
    }

    public function AauthAcessToken(){
        return $this->hasMany(OauthAccessToken::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [ 'role' => 'api2' ];
    }


}
