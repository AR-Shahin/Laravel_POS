<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function payment(){
        return $this->belongsTo(Payment::class,'id','invoice_id');
    }

    public function customer(){
        return $this->belongsTo(Customer::class,'customer_id','id');
    }

    public function invoiceItems(){
        return $this->hasMany(Invoice_Details::class,'invoice_id','id');
    }

}
