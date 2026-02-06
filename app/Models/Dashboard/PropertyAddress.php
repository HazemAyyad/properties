<?php

namespace App\Models\Dashboard;

use App\Models\City;
use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class PropertyAddress extends Model
{
    use HasFactory,HasTranslations;
     protected $guarded=[];
    public $translatable = [

        'full_address',
    ];
    public function country()
    {
        return $this->belongsTo (Country::class);
    }
    public function city()
    {
        return $this->belongsTo (City::class);
    }
    public function state()
    {
        return $this->belongsTo (State::class);
    }

}
