<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use function view;

class DashboardController extends Controller
{
    public function dashboard(){
        return view('backend.dashboard');
    }
}
