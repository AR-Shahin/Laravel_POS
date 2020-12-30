<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function response;
use function strtoupper;
use function view;

class UnitController extends Controller
{
    public function index(){
        return view('backend.unit.index',$this->data);
    }

    public function getAllUnits(){

        return response(Unit::latest()->get());
    }
    public function destroy(Request $request)
    {
        $cat = Unit::find($request->id);
        if($cat->delete()){
            return response()->json([
                'message' => 'Data deleted successfully!'
            ]);
        }
    }

    public function store(Request $request){

        $unit = Unit::where('name',$request->name)->first();
        if($unit){
            return response()->json([
                'message' => 'EXIST'
            ]);
        }else{
            $unit = new Unit();
            $unit->name = strtoupper($request->name);
            $unit->created_by = Auth::user()->id;
            if($unit->save()){
                return response()->json([
                    'message' => 'Data deleted successfully!',
                    'flag' => 'INSERT',
                ]);
            }
        }

    }

    public function makeActive(Request $request){
        $cat = Unit::find($request->id)->update([
            'status' => 1
        ]);
        return response()->json('SUCCESS');
    }

    public function makeInactive(Request $request){
        $cat = Unit::find($request->id)->update([
            'status' => 0
        ]);
        return response()->json('SUCCESS');
    }

    public function edit(Request $request){
        $unit = Unit::find($request->id);
        return response()->json($unit);
    }

    public function update(Request $request){
        $unit = Unit::where('name',$request->name)->first();
        if($unit){
            return response()->json([
                'message' => 'EXIST'
            ]);
        }else{
            $unit = Unit::find($request->id);
            $unit->name = strtoupper($request->name);
            $unit->updated_by = Auth::user()->id;
            if($unit->save()){
                return response()->json([
                    'message' => 'Unit Updated successfully!',
                    'flag' => 'UPDATE',
                ]);
            }
        }
    }
}
