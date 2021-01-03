<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Invoice_Details;
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
        if($request->has('category_id')){
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
            return $request->all();
            $invoice = new Invoice();
            $invoice->invoice_no = $request->input('invoice_no');
            $invoice->date = $request->input('date');
            $invoice->description = $request->input('description');
            $invoice->created_by = Auth::user()->id;

            DB::transaction(function () use ($request,$invoice){
                if($invoice->save()){
                    $countRows = count($request->input('category_id'));
                    for ($i = 0;$i<$countRows;$i++){
                        $invoice_details = new Invoice_Details();

                    }
                }
            });

        }else{
            return response()->json([
                'flag' => 'EMPTY',
                'message' => 'Sorry! Please Purchase something!'
            ]);
        }

    }
}
