<?php

namespace App\Models\Dashboard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class PropertyInformation extends Model
{
    use HasFactory,HasTranslations;
    protected $table = 'property_informations';
    protected $guarded = [];
    protected $casts = [
        'extra_features' => 'array',
        'furnished' => 'integer',
        'size' => 'float',
        'size_max' => 'float',
        'land_area_min' => 'float',
        'land_area_max' => 'float',
        'price_min' => 'float',
        'price_max' => 'float',
    ];
    public $translatable = [
        'content',
    ];
//    protected $appends=['photo'];
//    public function getPhotoAttribute()
//    {
//        $headers = get_headers($this->attributes['photo'], 1);
//        if (strpos($headers['Content-Type'], 'image/') !== false) {
//            return $this->attributes['photo'];
//        } else {
//            return asset('/assets/img/avatars/1.png');
//        }
//    }

}
