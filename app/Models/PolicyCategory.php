<?php

namespace App\Models;

use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;
use Spatie\Sluggable\HasTranslatableSlug;
class PolicyCategory extends Model
{
    use HasFactory,HasTranslations;
    protected $table='policy_categories';
    protected $guarded=[];
    // Define which fields are translatable
    public $translatable = [
        'title'
    ];




}
