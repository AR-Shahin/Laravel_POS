<?php

namespace App\Models;

use function date;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use function strtotime;

class Invoice_Details extends Model
{
    use HasFactory;
    public function getDateAttribute($date){
        return date('Y-m-d',strtotime($date));
    }
    public function getCreatedAtAttribute($date){
        return date('Y-m-d',strtotime($date));
    }
    public function product(){
        return $this->belongsTo(Product::class);
    }
    public function invoice(){
        return $this->belongsTo(Invoice::class);
    }
    public function category(){
        return $this->belongsTo(Category::class);
    }


}
