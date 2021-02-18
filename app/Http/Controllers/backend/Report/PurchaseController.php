<?php

namespace App\Http\Controllers\backend\Report;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use Illuminate\Http\Request;
use function response;

class PurchaseController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->data['main_menu'] = 'Report';
    }
    public function index(){
        $this->data['sub_menu'] = 'Purchase_Report';
        return view('backend.report.purchase',$this->data);
    }
    public function dateWisePurchaseReport(Request $request){
        return response()->json(Purchase::with(['category','supplier','product'])->whereBetween('date',[$request->get('start_date',date('Y-m-d')),$request->get('end_date',date('Y-m-d'))])->latest()->get());

    }
}
