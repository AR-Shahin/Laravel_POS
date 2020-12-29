<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['name','supplier_id','unit_id','category_id','user_id','status','quantity'];

    public function category(){
        return $this->belongsTo(Category::class,'category_id','id');
    }
    public function unit(){
        return $this->belongsTo(Unit::class);
    }
    public function supplier(){
        return $this->belongsTo(Supplier::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
