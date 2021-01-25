<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use function response;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public $data = [];

    public function returnAjaxResponse($flag,$message=null,array $data = null,$code = null){
        return response()->json([
            'status' => $code,
            'flag' => $flag,
            'message' => $message,
            'data' => $data
        ]);
    }
}
