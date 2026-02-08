<?php

namespace App\Models\Jordan;

use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    protected $table = 'villages';

    protected $primaryKey = 'village_id';

    public $timestamps = false;

    protected $fillable = ['department_id', 'village_name_ar', 'village_name_en'];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }

    public function hods()
    {
        return $this->hasMany(Hod::class, 'village_id', 'village_id');
    }

    public function hays()
    {
        return $this->hasMany(Hay::class, 'village_id', 'village_id');
    }
}
