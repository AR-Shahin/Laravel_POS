<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use function response;

class DefaultController extends Controller
{
    public function getAllSuppliers(){
        return response()->json([
            'flag' => 'OK',
            'data' => Supplier::has('products')->select('id','name')->latest()->get()
        ]);
    }

    public function getAllCategory(Request $request){
        return response()->json([
            'flag' => 'OK',
            'data' => Product::select('category_id')
                ->where('supplier_id',$request->supplier_id)
                ->groupBy('category_id')
                ->with('category')
                ->get()
        ]);
    }

    public function getAllProduct(Request $request){
        return response()->json([
            'flag' => 'OK',
            'data' => Product::where('category_id',$request->category_id)->get()
        ]);
    }


}
