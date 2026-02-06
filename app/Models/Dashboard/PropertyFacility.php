<?php

namespace App\Models\Dashboard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PropertyFacility extends Model
{
    use HasFactory;
     protected $guarded=[];
    public function facility()
    {
        return $this->belongsTo(Facility::class, 'facility_id');
    }


}
