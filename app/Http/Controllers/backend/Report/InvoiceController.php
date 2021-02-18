<?php

namespace App\Http\Controllers\backend\Report;

use App\Http\Controllers\Controller;
use App\Models\Invoice_Details;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->data['main_menu'] = 'Report';
    }
    public function index(){
        $this->data['sub_menu'] = 'Invoice_Report';
        return view('backend.report.invoice',$this->data);
    }
    public function dateWiseInvoiceReport(Request $request){
        return response()->json(Invoice_Details::where('status',1)->with(['category','invoice','product'])->whereBetween('date',[$request->get('start_date',date('Y-m-d')),$request->get('end_date',date('Y-m-d'))])->latest()->get());

    }
}
