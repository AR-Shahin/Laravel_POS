<?php

namespace App\Http\Controllers\backend\Report;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use function view;

class CreditCustomerController extends Controller
{
    public function index(){
        return view('backend.report.credit_customer');
    }

    public function getAllCreditCustomers(){
        return $data = Payment::with('cus')->where('paid_status','full_due')->orWhere('paid_status','partial_paid')->latest()->get();
    }
}
