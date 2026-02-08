<?php

namespace App\Models\Jordan;

use Illuminate\Database\Eloquent\Model;

class Hod extends Model
{
    protected $table = 'hods';

    protected $primaryKey = 'hod_id';

    public $timestamps = false;

    public $incrementing = false;

    protected $fillable = ['department_id', 'village_id', 'hod_name_ar', 'hod_name_en'];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }

    public function village()
    {
        return $this->belongsTo(Village::class, 'village_id', 'village_id');
    }

    public function hays()
    {
        return $this->hasMany(Hay::class, 'hod_id', 'hod_id');
    }
}
