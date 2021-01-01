<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;
protected $fillable = ['status','added_by','created_by','supplier_id','product_id','category_id','date','description','buying_quantity','purchase_no','buying_price','unit_price'];
    public function supplier(){
        return $this->belongsTo(Supplier::class,'supplier_id','id');
    }
    public function product(){
        return $this->belongsTo(Product::class,'product_id','id');
    }
    public function category(){
        return $this->belongsTo(Category::class,'category_id','id');
    }

}
