<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use function view;

class DashboardController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->data['main_menu'] = 'Dashboard';
        $this->data['sub_menu'] = 'Dashboard';
    }

    public function dashboard(){
        return view('backend.dashboard',$this->data);
    }
}
