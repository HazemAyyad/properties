<?php

namespace App\Models\Dashboard;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Support extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded = '';
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
    protected $appends=['support_type'];

    public function getSupportTypeAttribute()
    {
//        1 => general 2=> payment 3=> business 4=>shipping rate 5=> problem in website
//        6=>claims 7=>order normal 8=> order warehouse 9=> package warehouse
        if ($this->type==7){
            return 1;
        }
        elseif ($this->type==8){
//            0=>new 1=>ready to ship 2=>wait Shipping 3=>shipped out 4=>delivered 5=>Cancelled 6=>Need Pay
            $package=ShipPackageRequest::query()->where('id',$this->package_id)->first();
            if ($package){
                if ($package->status==3||$package->status==4){
                    return 2;
                }
                else{
                    return 3;
                }
            }else{
                return 0;
            }

        }
        elseif ($this->type==9){
            return 4;
        }
        else{
            return 5;
        }


    }
}
