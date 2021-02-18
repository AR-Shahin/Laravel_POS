<?php

namespace App\Http\Controllers\backend\Report;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\PaymentDetails;
use Carbon\Carbon;
use function dd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function view;

class CreditCustomerController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->data['main_menu'] = 'Report';
    }
    public function index(){
        $this->data['sub_menu'] = 'Credit_Report';
        return view('backend.report.credit_customer',$this->data);
    }

    public function getAllCreditCustomers(){
        return $data = Payment::with('cus')->where('paid_status','full_due')->orWhere('paid_status','partial_paid')->latest()->get();
    }

    public function updateInvoiceAmount(Request $request){
      //  return Payment::where('invoice_id',$request->invoice_id)->first()->paid_amount;
      //  dd($request);
        $payment = Payment::find($request->payment_id);
        $pd = new PaymentDetails();
        $payment->invoice_id = $request->invoice_id;
        $payment->customer_id = $request->customer_id;
//        $payment->discount_amount    = 0;
        $payment->paid_status  = $request->payment_status;
        if($request->payment_status == 'full_paid'){
            $payment->paid_amount   = Payment::where('invoice_id',$request->invoice_id)->first()->paid_amount + $request->due_amount;
            $payment->due_amount   = 0;
           // $payment->total_amount    = $request->due_amount;
            $pd->current_paid_amount = $request->due_amount;
        }else{
            $payment->paid_amount  = Payment::where('invoice_id',$request->invoice_id)->first()->paid_amount + $request->partial_amount;
            $payment->due_amount   =Payment::where('invoice_id',$request->invoice_id)->first()->due_amount - $request->partial_amount;
           // $payment->total_amount    = $request->partial_amount;
            $pd->current_paid_amount = $request->partial_amount;
        }
        if($payment->save()){
            $pd->invoice_id = $request->invoice_id;
            $pd->date = Carbon::now();
            $pd->created_by = Auth::user()->id;
            $pd->updated_by = Auth::user()->id;
            $pd->save();
            return 1;
        }else{
            return 0;
        }

        dd($request);
    }
}
