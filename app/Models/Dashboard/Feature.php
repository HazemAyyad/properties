<?php

namespace App\Models\Dashboard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Feature extends Model
{
    use HasFactory,HasTranslations;
     protected $guarded=[];
    // Define which fields are translatable
    public $translatable = [
        'name'
    ];

    public function featureCategory()
    {
        return $this->belongsTo(FeatureCategory::class, 'category_id');
    }


}
