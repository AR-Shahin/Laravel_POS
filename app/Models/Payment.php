<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    public function customer(){
        return $this->belongsTo(Customer::class,'id','customer_id');
    }
    public function cus(){
      return  $this->belongsTo(Customer::class,'customer_id','id');
    }

    public function getCreatedAtAttribute($value)
    {
        $carbonDate = new Carbon($value);
        return $carbonDate->format('Y-m-d |  h:i:s A');
    }
}
