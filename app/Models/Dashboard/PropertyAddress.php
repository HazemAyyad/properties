<?php

namespace App\Models\Dashboard;

use App\Models\City;
use App\Models\Country;
use App\Models\Jordan\Governorate;
use App\Models\Jordan\Department;
use App\Models\Jordan\Village;
use App\Models\Jordan\Hod;
use App\Models\Jordan\Hay;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class PropertyAddress extends Model
{
    use HasFactory, HasTranslations;

    protected $guarded = [];

    public $translatable = ['full_address'];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function governorate()
    {
        return $this->belongsTo(Governorate::class, 'governorate_id', 'governorate_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }

    public function village()
    {
        return $this->belongsTo(Village::class, 'village_id', 'village_id');
    }

    public function hod()
    {
        return $this->belongsTo(Hod::class, 'hod_id', 'hod_id')
            ->whereColumn('hods.department_id', 'property_addresses.department_id')
            ->whereColumn('hods.village_id', 'property_addresses.village_id');
    }

    public function hay()
    {
        return $this->belongsTo(Hay::class, 'hay_id', 'hay_id')
            ->whereColumn('hays.department_id', 'property_addresses.department_id')
            ->whereColumn('hays.village_id', 'property_addresses.village_id')
            ->whereColumn('hays.hod_id', 'property_addresses.hod_id');
    }

    /**
     * Get formatted address for display (Jordan or country/state/city)
     */
    public function getDisplayAddressAttribute(): string
    {
        $fullAddr = is_string($this->full_address ?? null) ? $this->full_address : (is_array($this->full_address ?? null) ? ($this->full_address['ar'] ?? $this->full_address['en'] ?? '') : '');
        $parts = array_filter([
            $fullAddr,
            $this->governorate?->governorate_name_ar ?? $this->governorate?->governorate_name_en,
            $this->department?->department_name_ar ?? $this->department?->department_name_en,
            $this->village?->village_name_ar ?? $this->village?->village_name_en,
            $this->plot_number,
            $this->city?->name,
            $this->state?->name,
            $this->country?->name,
        ]);
        return implode(', ', $parts) ?: '-';
    }
}
