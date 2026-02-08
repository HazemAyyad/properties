<?php

namespace App\Models\Jordan;

use Illuminate\Database\Eloquent\Model;

class Governorate extends Model
{
    protected $table = 'governorates';

    protected $primaryKey = 'governorate_id';

    public $timestamps = false;

    protected $fillable = ['governorate_name_ar', 'governorate_name_en'];

    public function departments()
    {
        return $this->hasMany(Department::class, 'governorate_id', 'governorate_id');
    }
}
