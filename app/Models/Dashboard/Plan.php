<?php

namespace App\Models\Dashboard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 use Spatie\Translatable\HasTranslations;

class Plan extends Model
{
    use HasFactory,HasTranslations;
     protected $guarded=[];
    // Define which fields are translatable
    // Note: extra_support is deprecated; plan features are the single source of truth (see plan_features table).
    // Column plans.extra_support is kept temporarily for backward compatibility; do not use in app logic.
    public $translatable = [
        'title',
        'description',
    ];

    public const UNLIMITED_PROPERTIES = -1;

    public const SLUG_TRIAL = 'trial';

    public function isDefault(): bool
    {
        return $this->slug === self::SLUG_TRIAL || (!empty($this->is_default));
    }
    public function features()
    {
        return $this->hasMany(PlanFeature::class,'plan_id','id');
    }

    public function users()
    {
        return $this->hasMany(\App\Models\User::class, 'plan_id');
    }

    public function isUnlimitedProperties(): bool
    {
        return $this->number_of_properties === self::UNLIMITED_PROPERTIES;
    }

    public function getNumberOfPropertiesDisplayAttribute(): string
    {
        return $this->isUnlimitedProperties() ? __('Unlimited') : (string) $this->number_of_properties;
    }



}
