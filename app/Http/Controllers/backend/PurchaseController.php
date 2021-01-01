<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use Carbon\Carbon;
use function count;
use function dd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function response;
use function view;

class PurchaseController extends Controller
{
    public function index(){
        $this->data['purchases'] = Purchase::latest()->get();
        return view('backend.purchase.index',$this->data);
    }

    public function store(Request $request){
        $check = $request->category_id;
        if($check == null){
            return response()->json([
                'flag' => 'EMPTY',
                'message' => 'Sorry! Please Purchase something!'
            ]);
        }else{
            $count = count($request->category_id);
            for ($i=0;$i<$count;$i++){
                $p = new Purchase();
                $p->date = $request->date[$i];
                $p->category_id = $request->category_id[$i];
                $p->supplier_id = $request->supplier_id[$i];
                $p->product_id = $request->product_id[$i];
                $p->purchase_no = $request->purchase_no[$i];
                $p->description = $request->description[$i];
                $p->buying_price = $request->buying_price[$i];
                $p->unit_price = $request->unit_price[$i];
                $p->buying_quantity = $request->buying_qty[$i];
                $p->created_by = Auth::user()->id;
                $p->updated_by = Auth::user()->id;
                $p->save();
            }
            return response()->json([
                'flag' => 'INSERT',
                'message' => 'Data Added Successfully!'
            ]);
        }
    }

    public function getAllPurchase(){
        return response(Purchase::with('category','product','supplier')
            -> latest()->get());

    }
}
