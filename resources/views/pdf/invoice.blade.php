<!doctype html>
<html lang="en">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport"
      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<body>
<style>
    .table{
        border:1px solid;
    }
</style>
</body>
<div class="">
    <h4 style="text-align: center">Purchase Invoice</h4>
    <hr>
    <div>
        <h6>Invoice No : #{{$invoice->id}}</h6>
        <span> <em>Customer Details</em></span> <br>
        <b>Name : </b> <span>{{$invoice->customer->name}}</span> <br>
        <b>Email : </b> <span>{{$invoice->customer->email}}</span> <br>
        <b>Phone : </b> <span>{{$invoice->customer->phone}}</span> <br>
        <b>Address : </b> <span>{{$invoice->customer->address}}</span> <br> <br>
    </div>
    <table class="table table-bordered text-center table-responsive-sm" style="width: 100%;table-border: 1">
        <tr>
            <th>Date</th>
            <td>{{$invoice->created_at->format('d-m-Y')}}</td>
            <th>Status</th>
            <td>Active</td>
            <td></td>
        </tr>
        <tr><td colspan="5"><h5 style="text-align: center">Invoice Items</h5></td></tr>
        <tr><th>Sl</th> <th>Name</th> <th>Quantity</th> <th>Price</th> <th>Total</th></tr>
        {{--dymanic--}}
        <?php
            $subTotal = 0;
        ?>
        @foreach($items as $key => $item)
            <tr>
                <td>{{++$key}}</td>
                <td>{{$item->product->name}}</td>
                <td>{{$item->selling_qty}}</td>
                <td>{{$item->unit_price}}</td>
                <td>{{$item->selling_price}}</td>
            </tr>
            @php
                $subTotal += $item->selling_price;
            @endphp
        @endforeach
        <tr><td colspan="4" class="text-right">Total</td><td>{{$subTotal}}</td></tr>
        <tr><td colspan="5"><h5 class="text-center">Payment Details</h5></td></tr>
        <tr><th>Payment Status</th><th>Total</th> <th>Discount</th> <th>Paid</th> <th>Due</th></tr>
        <tr><th>{{$payment->paid_status}}</th><th>{{$payment->total_amount}}</th> <th>{{$payment->discount_amount}}</th> <th>{{$payment->paid_amount}}</th> <th>{{$payment->due_amount}}</th></tr>
        <hr>
        <br>
<span style="border-top: 1px solid" class="d-inline mt-3">Signture</span>
    </table>
</div>
</html>