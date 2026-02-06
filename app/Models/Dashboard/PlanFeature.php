<?php

namespace App\Models\Dashboard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 use Spatie\Translatable\HasTranslations;

class PlanFeature extends Model
{
    use HasFactory,HasTranslations;
     protected $guarded=[];
    // Define which fields are translatable
    public $translatable = [
        'title',

    ];



}
