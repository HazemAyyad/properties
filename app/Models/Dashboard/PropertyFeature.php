<?php

namespace App\Models\Dashboard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PropertyFeature extends Model
{
    use HasFactory;
     protected $guarded=[];
     protected $table='property_features';
    public function feature()
    {
        return $this->belongsTo(Feature::class, 'feature_id');
    }
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
