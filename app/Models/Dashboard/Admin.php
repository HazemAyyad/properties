<?php

namespace App\Models\Dashboard;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use    HasFactory, Notifiable,HasRoles;

    protected $guarded=[];
//    protected $appends=['photo'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

//    public function getPhotoAttribute()
//    {
//        $headers = get_headers($this->attributes['photo'], 1);
//        if (strpos($headers['Content-Type'], 'image/') !== false) {
//            return $this->attributes['photo'];
//        } else {
//            return asset('/assets/img/avatars/1.png');
//        }
//    }

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
