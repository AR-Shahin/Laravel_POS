<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Invoice_Details;
use App\Models\Payment;
use App\Models\PaymentDetails;
use function count;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index(){
        $this->data['invoices'] = Invoice::latest()->get();
        $invoiceData = Invoice::latest()->first();
        if($invoiceData == null){
            $first =  0;
            $this->data['invoice_no'] = $first + 1;
        }else{
            $invoiceData = Invoice::latest()->first()->invoice_no;
            $this->data['invoice_no'] = $invoiceData + 1;
        }
        return view('backend.invoice.index',$this->data);
    }

    public function store(Request $request){
        // return $request->all();
        if($request->has('category_id')){
            if($request->input('payment_status') == null){
                return response()->json([
                    'flag' => 'EMPTY_PAYMENT',
                    'message' => 'Sorry! Select Payment Option'
                ]);
            }
            if($request->input('payment_status') == 'partial_paid' && $request->input('partial_amount') == null){
                return response()->json([
                    'flag' => 'EMPTY_PAYMENT_PARTIAL',
                    'message' => 'Sorry! Enter Partial Amount!'
                ]);
            }
            if($request->input('customer_id') == null && ($request->input('cusName') == null)){
                return response()->json([
                    'flag' => 'EMPTY_CUS',
                    'message' => 'Sorry! Please Select Customer!'
                ]);
            }

            if($request->customer_id == 'add_new_customer' &&($request->input('cusName') == null) ){
                return response()->json([
                    'flag' => 'EMPTY_NAME',
                    'message' => 'Sorry! Please Enter Customer Details!'
                ]);
            }
            if($request->customer_id == 'add_new_customer' &&($request->input('cusName') == null) ){
                return response()->json([
                    'flag' => 'EMPTY_NAME',
                    'message' => 'Sorry! Please Enter Customer Details!'
                ]);
            }
            $invoice = new Invoice();
            $invoice->invoice_no = $request->input('invoice_no');
            $invoice->date = $request->input('date');
            $invoice->description = $request->input('description');
            $invoice->created_by = Auth::user()->id;
            $invoice->updated_by = Auth::user()->id;

            DB::transaction(function () use ($request,$invoice){
                if($invoice->save()){
                    $countRows = count($request->input('category_id'));
                    for ($i = 0;$i<$countRows;$i++){
                        $invoice_details = new Invoice_Details();
                        $invoice_details->date = $request->input('date');
                        $invoice_details->invoice_id = $request->input('invoice_no');
                        $invoice_details->category_id = $request->category_id[$i];
                        $invoice_details->product_id = $request->product_id[$i];
                        $invoice_details->selling_qty = $request->selling_qty[$i];
                        $invoice_details->unit_price = $request->unit_price[$i];
                        $invoice_details->selling_price = $request->selling_price[$i];
                        $invoice_details->save();
                    }
                    if($request->input('customer_id') == 'add_new_customer'){
                        $customer = new Customer();
                        $customer->name = $request->input('cusName');
                        $customer->email = $request->input('cusEmail');
                        $customer->phone = $request->input('cusPhone');
                        $customer->address = $request->input('cusAddress');
                        $customer->save();
                        $customerId = $customer->id;
                    }else{
                        $customerId = $request->input('customer_id');
                    }
                    $payment = new Payment();
                    $payment_details = new PaymentDetails();

                    $payment->invoice_id = $invoice->id;
                    $payment->customer_id = $customerId;
                    $payment->paid_status = $request->input('payment_status');
                    $payment->discount_amount = $request->input('discount');
                    $payment->total_amount = $request->input('subTotal');

                    if($request->payment_status == 'full_paid'){
                        $payment->paid_amount = $request->input('subTotal');
                        $payment->due_amount = 0;
                        $payment_details->current_paid_amount = $request->input('subTotal');
                    }else if($request->payment_status == 'full_due'){
                        $payment->paid_amount = 0;
                        $payment->due_amount = $request->input('subTotal');
                        $payment_details->current_paid_amount = 0;
                    }else if($request->payment_status == 'partial_paid'){
                        $payment->paid_amount = $request->input('partial_amount');
                        $payment->due_amount =  $request->subTotal - $request->input('partial_amount');
                        $payment_details->current_paid_amount = $request->input('partial_amount');
                    }
                    $payment->save();
                    $payment_details->created_by = Auth::user()->id;
                    $payment_details->updated_by = Auth::user()->id;
                    $payment_details->invoice_id = $invoice->id;
                    $payment_details->date = $request->input('date');
                    $payment_details->save();
                }
            });

            return response()->json([
                'flag' => 'SUCCESS',
                'message' => 'success! Data save successfully!'
            ]);

        }else{
            return response()->json([
                'flag' => 'EMPTY',
                'message' => 'Sorry! Please Purchase something!'
            ]);
        }

    }
}
