<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use Illuminate\Http\Request;
use function view;

class PurchaseController extends Controller
{
    public function index(){
        $this->data['purchases'] = Purchase::latest()->get();
        return view('backend.purchase.index',$this->data);
    }
}
