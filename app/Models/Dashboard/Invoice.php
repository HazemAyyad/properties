<?php

namespace App\Models\Dashboard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded=[];
    protected $appends=['payments_response'];
    public function getPaymentsResponseAttribute()
    {

        if ($this->attributes['payments_response'] != null) {
            return json_decode($this->attributes['payments_response'],true);
        } else {
            return [];
        }
    }
}
