<?php

namespace App\Models\Jordan;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'departments';

    protected $primaryKey = 'department_id';

    public $timestamps = false;

    protected $fillable = ['governorate_id', 'department_name_ar', 'department_name_en'];

    public function governorate()
    {
        return $this->belongsTo(Governorate::class, 'governorate_id', 'governorate_id');
    }

    public function villages()
    {
        return $this->hasMany(Village::class, 'department_id', 'department_id');
    }

    public function hods()
    {
        return $this->hasMany(Hod::class, 'department_id', 'department_id');
    }
}
