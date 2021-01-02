<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(){
        $this->data['invoices'] = Invoice::latest()->get();
        $invoiceData = Invoice::latest()->first();
        if($invoiceData == null){
            $first =  0;
            $this->data['invoice_no'] = $first + 1;
        }else{
            $invoiceData = Invoice::latest()->first()->invoice_no;
            $this->data['invoice_no'] = $invoiceData + 1;
        }
        return view('backend.invoice.index',$this->data);
    }
}
