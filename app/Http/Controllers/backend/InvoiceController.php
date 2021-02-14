<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Invoice_Details;
use App\Models\Payment;
use App\Models\PaymentDetails;
use App\Models\Product;
use Barryvdh\DomPDF\Facade as PDF;
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
        // return $this->data['invoice_no'];
        return view('backend.invoice.index',$this->data);
    }

    public function store(Request $request){
        // dd( $request->all());
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
            $invoice = new Invoice();
            $invoice->invoice_no = $request->input('invoice_no');
            $invoice->customer_id = $customerId;
            $invoice->date = $request->input('date');
            $invoice->description = $request->input('description');
            $invoice->created_by = Auth::user()->id;
            $invoice->updated_by = Auth::user()->id;

            DB::transaction(function () use ($request,$invoice,$customerId){
                if($invoice->save()){
                    $countRows = count($request->input('category_id'));
                    // dd($countRows);
                    for ($i = 0;$i<$countRows;$i++){
                        $invoice_details = new Invoice_Details();
                        $invoice_details->date = $request->input('date');
                        $invoice_details->invoice_id = $invoice->id;
                        $invoice_details->category_id = $request->category_id[$i];
                        $invoice_details->product_id = $request->product_id[$i];
                        $invoice_details->selling_qty = $request->selling_qty[$i];
                        $invoice_details->unit_price = $request->unit_price[$i];
                        $invoice_details->selling_price = $request->selling_price[$i];
                        $invoice_details->save();
                    }
                    //changing portion
//                    if($request->input('customer_id') == 'add_new_customer'){
//                        $customer = new Customer();
//                        $customer->name = $request->input('cusName');
//                        $customer->email = $request->input('cusEmail');
//                        $customer->phone = $request->input('cusPhone');
//                        $customer->address = $request->input('cusAddress');
//                        $customer->save();
//                        $customerId = $customer->id;
//                    }else{
//                        $customerId = $request->input('customer_id');
//                    }

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

    public function getAllInvoice(){
        return response([
            'data' => Invoice::with(['payment','customer'])
                -> latest()->get()
        ]);
    }

    public function approve(Request $request){
        $products =  Invoice_Details::select('product_id','selling_qty')->where('invoice_id',$request->id)->get();
        foreach ($products as  $product){
            Product::where('id',$product->product_id)->decrement('quantity', $product->selling_qty);
        }
        $update = Invoice::find($request->id)->update([
            'status' => 1
        ]);
        if($update){
            return $this->returnAjaxResponse('UPDATE','Approve Invoice!');
        }
    }

    public function view(Request $request){
        //  return DB::select("SELECT invoice__details.*,payments.*,payment_details.*,invoices.* FROM invoice__details INNER JOIN invoices ON invoice__details.invoice_id = invoices.id INNER JOIN payments ON payments.invoice_id = invoices.id INNER JOIN payment_details ON payment_details.invoice_id = invoices.id WHERE invoices.id = $request->id ");
        $data =[
            'self' => Invoice::with('customer')->find($request->id),
            'items' => Invoice_Details::with('product')->where('invoice_id',$request->id)->get(),
            'payment' => Payment::where('invoice_id',$request->id)->first(),
            'credit_payment' => Payment::where('invoice_id',$request->id)->get(),
            'payment_details' => PaymentDetails::where('invoice_id',$request->id)->first(),
            'PD' => PaymentDetails::where('invoice_id',$request->id)->get(),
        ];
        return $this->returnAjaxResponse('success','',$data);
    }

    public function destroy(Request $request){
        $id =  $request->input('id');
        Invoice::find($id)->delete();
        Invoice_Details::where('invoice_id',$id)->delete();
        PaymentDetails::where('invoice_id',$id)->delete();
        Payment::where('invoice_id',$id)->delete();

        return $this->returnAjaxResponse('DELETE','Data Delete Successfully!',[],200);

    }

    public function printInvoice(Request $request){
        $this->data['items'] = Invoice_Details::with('product')->where('invoice_id',$request->id)->get();
        $this->data['invoice'] = Invoice::with('customer')->find($request->id);
        $this->data['payment'] = Payment::where('invoice_id',$request->id)->first();
        $customPaper = array(0,0,720,1000);
        PDF::setOptions(['dpi' => 200, 'defaultFont' => 'arial']);
        $pdf = PDF::loadView('pdf.invoice',$this->data);
        return $pdf->stream("invoice-$request->id.pdf");
       // return view('pdf.invoice');
    }

}
