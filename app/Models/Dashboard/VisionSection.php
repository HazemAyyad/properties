<?php

namespace App\Models\Dashboard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class VisionSection extends Model
{
    use HasFactory, HasTranslations;

    protected $guarded = [];

    public $translatable = [
        'vision_title',
        'vision_description',
        'goals_title',
    ];

    public function goals()
    {
        return $this->hasMany(VisionGoal::class)->orderBy('sort_order');
    }
}
