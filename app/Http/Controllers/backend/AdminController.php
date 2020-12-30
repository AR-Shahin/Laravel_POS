<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(){
        $this->data['admin'] = User::latest()->get();
        return view('backend.admin.index',$this->data);
    }
    public function getAllAdmin(){

        return response(User::latest()->get());
    }

    public function destroy(Request $request)
    {
        $sup = User::find($request->id);
        if($sup->delete()){
            return response()->json([
                'message' => 'Data deleted successfully!'
            ]);
        }
    }

    public function store(Request $request){


    }

    public function edit(Request $request){
        $sup = Product::with(['category','unit','user','supplier'])->find($request->id);
        return response()->json($sup);
    }

    public function update(Request $request){
        $up = Product::find($request->id)->update($request->all());
        if($up){
            return response()->json([
                'message' => 'Data Updated successfully!',
                'flag' => 'UPDATE',
            ]);
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
