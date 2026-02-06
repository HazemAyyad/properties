<?php

namespace App\Models\Dashboard;

use App\Traits\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use HasFactory,SoftDeletes,Sluggable;
    protected $guarded=[];
    public function user()
    {
        return $this->belongsTo(Admin::class);
    }
    public function category()
    {
        return $this->belongsTo(BlogCategory::class);
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = $model->generateUniqueSlug($model->title_en); // Assuming 'title' is the field you want to base the slug on
            }
        });
    }
    protected $appends=['full_tags'];
    public function getFullTagsAttribute()
    {
        $tags=json_decode($this->attributes['tags'],true);
        $tags_full=[];
        foreach ($tags as $tag){
            $tags_full[]=BlogTag::query()->where('id',$tag)->first();
        }
        return $tags_full;
    }
}
