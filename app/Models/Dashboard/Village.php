<?php

namespace App\Models\Dashboard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Village extends Model
{
    use HasFactory,SoftDeletes,HasTranslations;
    protected $guarded=[];
    // Define which fields are translatable
    public $translatable = [
        'name',

    ];


}
