<?php

namespace App\Models\Dashboard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class FeatureCategory extends Model
{
    use HasFactory,SoftDeletes,HasTranslations;
    protected $table='feature_categories';
    protected $guarded=[];
    // Define which fields are translatable
    public $translatable = [
        'name'
    ];



    public function features()
    {
        return $this->hasMany(Feature::class,'category_id','id');
    }

}
