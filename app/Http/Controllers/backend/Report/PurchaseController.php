<?php

namespace App\Http\Controllers\backend\Report;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use Illuminate\Http\Request;
use function response;

class PurchaseController extends Controller
{
    public function index(){
        return view('backend.report.purchase');
    }
    public function dateWisePurchaseReport(Request $request){
        return response()->json(Purchase::with(['category','supplier','product'])->whereBetween('date',[$request->get('start_date',date('Y-m-d')),$request->get('end_date',date('Y-m-d'))])->latest()->get());

    }
}
