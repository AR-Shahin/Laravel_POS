<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function response;

class CategoryController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->data['main_menu'] = 'Product';
    }
    public function index(){
        $this->data['sub_menu'] = 'Category';
        return view('backend.category.index',$this->data);
    }

    public function getAllCategory(){

        return response(Category::latest()->get());
    }
    public function destroy(Request $request)
    {
        $cat = Category::find($request->id);
        if($cat->delete()){
            return response()->json([
                'message' => 'Data deleted successfully!'
            ]);
        }
    }

    public function store(Request $request){
        $unit = Category::where('name',$request->name)->first();
        if($unit){
            return response()->json([
                'message' => 'EXIST'
            ]);
        }else{
            $unit = new Category();
            $unit->name = ucwords($request->name);
            $unit->created_by = Auth::user()->id;
            if($unit->save()){
                return response()->json([
                    'message' => 'Data Save successfully!',
                    'flag' => 'INSERT',
                ]);
            }
        }

    }

    public function makeActive(Request $request){
         Category::find($request->id)->update([
            'status' => 1,
        ]);
        return response()->json('SUCCESS');
    }

    public function makeInactive(Request $request){
        Category::find($request->id)->update([
            'status' => 0,
            'updated_by'=>1
        ]);
        return response()->json('SUCCESS');
    }

    public function edit(Request $request){
        $cat = Category::find($request->id);
        return response()->json($cat);
    }

    public function update(Request $request){
        $unit = Category::where('name',$request->name)->first();
        if($unit){
            return response()->json([
                'message' => 'EXIST'
            ]);
        }else{
            $unit = Category::find($request->id);
            $unit->name = ucwords($request->name);
            $unit->updated_by = Auth::user()->id;
            if($unit->save()){
                return response()->json([
                    'message' => 'Data Updated successfully!',
                    'flag' => 'UPDATE',
                ]);
            }
        }
    }
}
