<?php

namespace App\Models\Dashboard;

use App\Models\City;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded='';
    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
