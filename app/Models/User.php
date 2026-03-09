<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Dashboard\Plan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;
    protected $guard = 'web';

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function planUpgradeRequests()
    {
        return $this->hasMany(PlanUpgradeRequest::class);
    }

    public function properties()
    {
        return $this->hasMany(Property::class, 'user_id');
    }
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function isFavorited($propertyId)
    {
        return $this->favorites()->where('property_id', $propertyId)->exists();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded='';

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
            'subscription_started_at' => 'datetime',
            'subscription_ends_at' => 'datetime',
        ];
    }

    /**
     * Get the user's avatar URL. Falls back to a default placeholder when photo is null/empty/invalid.
     */
    public function getAvatarUrlAttribute(): string
    {
        $photo = $this->attributes['photo'] ?? null;
        if (empty($photo) || !is_string($photo)) {
            return 'data:image/svg+xml,' . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 128 128" width="128" height="128"><rect width="128" height="128" fill="#e0e0e0"/><circle cx="64" cy="48" r="24" fill="#999"/><ellipse cx="64" cy="110" rx="40" ry="30" fill="#999"/></svg>');
        }
        $path = ltrim(str_replace('/public', '', $photo), '/');
        $url = asset($path);
        return str_replace('/public/public/', '/public/', $url);
    }
}
