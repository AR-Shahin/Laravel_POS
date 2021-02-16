<?php

namespace App\Http\Controllers\backend\Report;

use App\Http\Controllers\Controller;
use App\Models\Invoice_Details;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(){
        return view('backend.report.invoice');
    }
    public function dateWiseInvoiceReport(Request $request){
        return response()->json(Invoice_Details::with(['category','invoice','product'])->whereBetween('date',[$request->get('start_date',date('Y-m-d')),$request->get('end_date',date('Y-m-d'))])->latest()->get());

    }
}
