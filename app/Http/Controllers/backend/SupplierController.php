<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use function ucwords;

class SupplierController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->data['main_menu'] = 'User';
    }

    public function index(){
        $this->data['sub_menu'] = 'Supplier';
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

        $email = Supplier::where('email',$request->email)->first();
        $phone = Supplier::where('phone',$request->phone)->first();
        if($email){
            return response()->json([
                'flag' => 'Email_EXIST',
                'message' => 'Email Already Taken.'
            ]);
        }
        elseif ($phone){
            return response()->json([
                'flag' => 'Phone_EXIST',
                'message' => 'Phone Already Taken.'
            ]);
        }
        else{
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
        $email = Supplier::where('email',$request->email)->first();
        $phone = Supplier::where('phone',$request->phone)->first();
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
            $up = Supplier::find($request->id)->update($request->all());
            if($up){
                return response()->json([
                    'message' => 'Data Updated successfully!',
                    'flag' => 'UPDATE',
                ]);
            }
        }
    }
}
