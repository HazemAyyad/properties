<?php

namespace App\Models\Dashboard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class VisionGoal extends Model
{
    use HasFactory, HasTranslations;

    protected $guarded = [];

    public $translatable = [
        'title',
        'description',
    ];

    public function section()
    {
        return $this->belongsTo(VisionSection::class, 'vision_section_id');
    }
}
