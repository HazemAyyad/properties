<?php

namespace App\Models;

use App\Models\Dashboard\Category;
use App\Models\Dashboard\PropertyAddress;
use App\Models\Dashboard\PropertyFacility;
use App\Models\Dashboard\PropertyFeature;
use App\Models\Dashboard\PropertyImage;
use App\Models\Dashboard\PropertyInformation;
use App\Models\Dashboard\PropertyPrice;
use App\Models\Dashboard\PropertyReviews;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Translatable\HasTranslations;
use Spatie\Sluggable\HasTranslatableSlug;

class Property extends Model
{
    use HasFactory,SoftDeletes,HasTranslations;
    protected $guarded=[];
    
    public $translatable = ['title','description','slug'];
    public function images()
    {
        return $this->hasMany(PropertyImage::class);
    }
    public function features()
    {
        return $this->hasMany(PropertyFeature::class);
    }
    public function reviews()
    {
        return $this->hasMany(PropertyReviews::class);
    }
    public function facilities()
    {
        return $this->hasMany(PropertyFacility::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function price()
    {
        return $this->hasOne (PropertyPrice::class);
    }
    public function more_info()
    {
        return $this->hasOne (PropertyInformation::class);
    }
    public function address()
    {
        return $this->hasOne(PropertyAddress::class)->with(['city','country','state']);
    }
    public function isFavorited()
    {
        $userId = Auth::id();
        return $this->favorites()->where('user_id', $userId)->exists();
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
    
    

}