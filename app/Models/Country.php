<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    public function states()
    {
        return $this->hasMany(State::class);
    }
    // Define the accessor for the currency
    public function getCurrencyAttribute()
    {
        $others = json_decode($this->attributes['others'], true);
        return $others['currency'] ?? null;
    }
}
