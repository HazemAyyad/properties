<?php

namespace App\Models\Dashboard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Messages extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded = '';
    public function support(){
        return $this->belongsTo(Support::class,'support_id','id');
    }
    public function team(){
        return $this->belongsTo(Admin::class,'team_id','id');
    }
}
