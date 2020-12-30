<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use const false;
use function file_exists;
use Illuminate\Http\Request;
use function in_array;
use function response;
use const true;
use Illuminate\Support\Facades\Hash;
use function unlink;

class AdminController extends Controller
{
    private function isPermittedExtension($ext){
        $permit = ['png','jpg','jpeg'];
        if(in_array($ext,$permit)){
            return true;
        }
        return false;
    }
    public function index(){
        $this->data['admin'] = User::latest()->get();
        return view('backend.admin.index',$this->data);
    }
    public function getAllAdmin(){
        return response(User::latest()->get());
    }

    public function destroy(Request $request)
    {
        $ob = User::findorFail($request->id);
        $img = $ob->image;
        $admin = User::find($request->id);
        if($admin->delete()){
            if(file_exists($img)){unlink($img);}
            return response()->json([
                'message' => 'Data deleted successfully!'
            ]);
        }
    }

    public function store(Request $request){
        $email = User::where('email',$request->email)->first();
        if($email){
            return response()->json([
                'flag' =>'EMAIL_NOT_MATCH',
                'message' => 'Email Already Exists!'
            ]);
        }
        $image = $request->file('image');
        $ext = $image->extension();
        if(!$this->isPermittedExtension($ext)){
            return response()->json([
                'flag' =>'EXT_NOT_MATCH',
                'message' => 'Extension Should be jpg,png,jpeg!'
            ]);
        }
        if(filesize($request->image)>=2000000){
            return response()->json([
                'flag' =>'BIG_SIZE',
                'message' => 'Image Size Should be smaller than 2MB!'
            ]);
        }
        $name =  hexdec(uniqid()) . '.' .$ext;
        $path = 'uploads/admin/';
        $last_image = $path.$name;
        $formData =  $request->all();
        $formData['password'] = Hash::make($request->password);
        $formData['image'] = $last_image ;
        if(User::create($formData)){
            $image->move($path,$last_image);
            return response()->json([
                'flag' =>'INSERT',
                'message' => 'Data save Successfully!'
            ]);
        }
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
