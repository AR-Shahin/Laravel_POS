<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Unit;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(){
        $this->data['cats'] = Category::where('status',1)->latest()->get();
        $this->data['units'] = Unit::where('status',1)->latest()->get();
        $this->data['suppliers'] = Supplier::latest()->get();
        return view('backend.product.index',$this->data);
    }
    public function getAllProduct(){

        return response(Product::with(['category','unit','user','supplier'])->latest()->get());
    }

    public function destroy(Request $request)
    {
        $sup = Product::find($request->id);
        if($sup->delete()){
            return response()->json([
                'message' => 'Data deleted successfully!'
            ]);
        }
    }

    public function store(Request $request){
        $product = Product::where('name',$request->name)->first();
        if($product){
            return response()->json([
                'flag' => 'Product_EXIST',
                'message' => 'Product Already Taken.'
            ]);
        }
        else{
            $product = new Product();
            $product->name = ucwords($request->name);
            $product->category_id = $request->category_id;
            $product->supplier_id = $request->supplier_id;
            $product->unit_id = $request->unit_id;
            $product->user_id = 1;

            if($product->save()){
                return response()->json([
                    'message' => 'Data Saved successfully!',
                    'flag' => 'INSERT',
                ]);
            }
        }

    }

    public function edit(Request $request){
        $sup = Product::find($request->id);
        return response()->json($sup);
    }

    public function update(Request $request){
        $email = Product::where('email',$request->email)->first();
        $phone = Product::where('phone',$request->phone)->first();
        if($email){
            return response()->json([
                'flag' => 'Email_EXIST',
                'message' => 'Email Already Taken.'
            ]);
        }
        elseif($phone){
            return response()->json([
                'flag' => 'Phone_EXIST',
                'message' => 'Phone Already Taken.'
            ]);
        }else{
            $up = Product::find($request->id)->update($request->all());
            if($up){
                return response()->json([
                    'message' => 'Data Updated successfully!',
                    'flag' => 'UPDATE',
                ]);
            }
        }
    }

    public function makeInactive(Request $request){
        Product::find($request->id)->update([
            'status' => 0,
        ]);
        return response()->json('SUCCESS');
    }

    public function makeActive(Request $request){
        Product::find($request->id)->update([
            'status' => 1,
        ]);
        return response()->json('SUCCESS');
    }
}