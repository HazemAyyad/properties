<?php

namespace App\Models;

use App\Traits\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Slider extends Model
{
    use HasFactory,HasTranslations;
    protected $guarded=[];
    protected $table='settings';
    // Define which fields are translatable
    public $translatable = [
        'value'
    ];
}
