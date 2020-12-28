<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use function ucwords;

class SupplierController extends Controller
{
    public function index(){
        return view('backend.supplier.index',$this->data);
    }

    public function getAllSupplier(){

        return response(Supplier::latest()->get());
    }
    public function destroy(Request $request)
    {
        $sup = Supplier::find($request->id);
        if($sup->delete()){
            return response()->json([
                'message' => 'Data deleted successfully!'
            ]);
        }
    }

    public function store(Request $request){

        $sup = Supplier::where('email',$request->email)->first();
        if($sup){
            return response()->json([
                'message' => 'EXIST'
            ]);
        }else{
            $sup = new Supplier();
            $sup->name = ucwords($request->name);
            $sup->address = ucwords($request->address);
            $sup->email = $request->email;
            $sup->phone = $request->phone;
            if($sup->save()){
                return response()->json([
                    'message' => 'Data Saved successfully!',
                    'flag' => 'INSERT',
                ]);
            }
        }

    }

    public function edit(Request $request){
        $sup = Supplier::find($request->id);
        return response()->json($sup);
    }

    public function update(Request $request){
        $sup = Supplier::where('name',$request->name)->first();
        if($sup){
            return response()->json([
                'message' => 'EXIST'
            ]);
        }else{
            $sup = Supplier::find($request->id);
            $sup->name = ucwords($request->name);
            $sup->address = ucwords($request->address);
            $sup->email = $request->email;
            $sup->phone = $request->phone;
            if($sup->save()){
                return response()->json([
                    'message' => 'Data Updated successfully!',
                    'flag' => 'UPDATE',
                ]);
            }
        }
    }
}
