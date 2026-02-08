<?php

namespace App\Models\Jordan;

use Illuminate\Database\Eloquent\Model;

class Hay extends Model
{
    protected $table = 'hays';

    protected $primaryKey = 'hay_id';

    public $timestamps = false;

    public $incrementing = false;

    protected $fillable = ['department_id', 'village_id', 'hod_id', 'hay_name_ar', 'hay_name_en'];

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
        return $this->belongsTo(Hod::class, 'hod_id', 'hod_id');
    }
}
