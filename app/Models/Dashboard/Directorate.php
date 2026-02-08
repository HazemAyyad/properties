<?php

namespace App\Models\Dashboard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Directorate extends Model
{
    use HasFactory,SoftDeletes,HasTranslations;
    protected $guarded=[];
    // Define which fields are translatable
    public $translatable = [
        'name',

    ];
    public function villages()
    {
        return $this->hasMany(Village::class);
    }

}
