@extends('layouts.master')
@section('title','Credit Customer')
@section('main_content')
    <div class="row no-gutters">
        <div class="col-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-info d-inline">Manage Credit Customer</h4>
                    <button type="button" class="btn btn-primary pull-right">
                        <i class="fa fa-print mr-1"></i> Print
                    </button>
                </div>
                <div class="card-body">
                    <div class="adv-table">
                        <table class=" display table table-bordered table-striped" id="creditCustomerTable">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Customer</th>
                                <th>I. No</th>
                                <th>I. Date</th>
                                <th>Due</th>
                                <th class="">Actions</th>
                            </tr>

                            <tbody id="creditCustomer">

                            </tbody>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('script')
<script>
    //get all credit customers
    function table_data_row(data) {
        var rows = '';
        var i = 0
        $.each(data ,function (key,value) {
            rows+= '<tr>';
            rows+= '<td>'+ ++i +'</td>';
            rows+= '<td>'+ value.cus.name +'</td>';
            rows+= '<td><b>Invoice No # </b>'+ value.id +'</td>';
            rows+= '<td>'+ value.created_at +'</td>';
            rows+= '<td> $ '+ value.due_amount +'</td>';
            rows+= '<td data-id="'+value.id+'" class="text-center">';
            rows+= '<a class="btn btn-sm btn-info text-light" id="payModalBtn" data-id="'+value.invoice_id+'" data-toggle="modal" data-target="#payModal"><i class="fa fa-dollar"></i> Pay</a> ';
            rows+= '<a class="btn btn-sm btn-success text-light" data-toggle="modal" data-target="#viewRow" id="viewRow" data-id="'+value.invoice_id+'" ><i class="fa fa-eye"></i> View</a> ';
            rows+= '</td>';
            rows+= '</tr>';
        });

        $('#creditCustomer').html(rows);
        $('#creditCustomerTable').dataTable();
    }
    getAllCreditCustomers();
    function getAllCreditCustomers() {
        $.ajax({
            url : "{{route('report.get-credit.customer')}}",
            method : 'GET',
            dataType: 'JSON',
            success : function (data) {
                table_data_row(data);
            }
        })
    }

    //pay amount
    $('body').on('click','#payModalBtn',function () {
        var id = $(this).data('id');
        $.ajax({
            url : "{{route('invoice.view')}}",
            method : 'GET',
            dataType : 'JSON',
            data : {id : id},
            success : function (response) {
              //  alert(55);
                // console.log(response.data.payment_details);
                // $('#cusName').innerText(response.self.customer.name);
                $('#viewSingleCredit').html(viewSinglePurchaseData(response.data));
            }
        })
    });
    function viewSinglePurchaseData(data) {

        var html = '';
        html += '<table class="table table-bordered">';
        html += '<tr><td>Date : </td>';
        html += '<td>' + data.self.date +'</td>';
        html += '<th colspan="2">Status : </th>';
        html += '<td>';
        if(data.self.status == 1){
            html += 'Approved';
        }else{
            html += 'Pending';
        }
        html += '</td>';
        html += '</tr>';
        html += '<tr><td colspan="5"><h5 class="text-center">Invoice Items</h5></td></tr>';
        html += '<tr><th>Sl</th> <th>Name</th> <th>Quantity</th> <th>Price</th> <th>Total</th></tr>';
        var sum = 0;
        $.each(data.items, function (key,value) {
            sum+= value.selling_price;
            html += '<tr><td>'+ ++key +'</td><td>'+ value.product.name+'</td><td>'+value.selling_qty+'</td> <td>'+value.unit_price+'</td><td>'+value.selling_price+'</td></tr>';
        });
        var discount = data.payment.discount_amount;
//console.log(discount);
//console.log(typeof discount);
        sum = sum - discount;
        html += '<tr><td colspan="4" class="text-right">Discount</td><td> - '+discount+'</td></tr>';
        html += '<tr><td colspan="4" class="text-right">Total</td><td>'+sum+'</td></tr>';
        html += '<tr><td colspan="4" class="text-right">Due</td><td><span id="dueAmount">'+data.payment.due_amount+'</span></td></tr>';

        //payment
        html += '<tr><td colspan="5"><h5 class="text-center">Payment Details</h5></td></tr>';
        $.each(data.PD, function (key,value) {
            html += '<tr><td colspan="3" class="text-right">Date </td> <td>'+ value.created_at+'</td> <td>'+ value.current_paid_amount+'</td></tr>';
        });

//        html += '<tr> <th>Status</th><th>Total</th> <th>Discount</th> <th>Paid</th> <th>Due</th></tr>';
//        html += '<tr><th>'+ data.payment.paid_status+'</th><th>'+data.payment.total_amount+'</th> <th>'+data.payment.discount_amount+'</th> <th>'+data.payment.paid_amount+'</th> <th>'+data.payment.due_amount+'</th></tr>';



        //new payment
        var row = '';
        row += '<hr><h6>Payment : </h6>';
        row += '<form id="creditCustomerForm">';
        row += '<div class="row"> <div class="col-12 col-md-6"> <div class="form-group">';
        row += '<select name="payment_status" id="payment_status" class="form-control">';
        row += ' <option value="">Select An Option</option>';
        row += '<option value="full_paid">Full Paid</option>';
        row += ' <option value="partial_paid">Partial Payment</option>';
        row += '</select>';
        row += '<div class="partial mt-2" style="display: none;">';
        row += '<input type="text" class="form-control form-control-sm" placeholder="Amount ..." name="partial_amount" id="partial_amount"></div>';

        row += '<input type="hidden" name="customer_id" id="customer_id" value="'+ data.self.customer_id+'">';
        row += '<input type="hidden" name="invoice_id" id="invoice_id" value="'+ data.payment.invoice_id+'">';
        row += '<input type="hidden" name="due_amount" id="due_amount" value="'+ data.payment.due_amount+'">';
        row += '<input type="hidden" name="payment_id" id="payment_id" value="'+ data.payment.id+'">';

        row += '<button class="btn btn-sm btn-success btn-block mt-2"> Pay</button>';
        row +=  '</div></div></div>';

        $('#newPayment').html(row);

        return html;
    }
    $('body').on('change','#payment_status',function () {
        var value = $(this).val();
        if(value == 'partial_paid'){
            $('.partial').show();
        }else{
            $('.partial').hide();
        }
    });

    $('body').on('keyup','#partial_amount',function () {
        var dueAmount = $('#dueAmount').text();
       // console.log(dueAmount);
        if(Number(dueAmount) <  $(this).val()){
            setNotifyAlert('Value Overloaded!!','error');
            $('#partial_amount').addClass('border-danger');
        }else{
            $('#partial_amount').removeClass('border-danger');
        }
    });

    //submit form
    $('body').on('submit','#creditCustomerForm',function (e) {
        e.preventDefault();
        if($('#payment_status').val() === ''){
            setNotifyAlert('Select Payment Type!');
            return;
        }
        var ifHasAmount =  $('#partial_amount').val();
        if($('#payment_status').val() === 'partial_paid' && ifHasAmount == ''){
            setSwalAlert('error','','Enter Number of Amount!');
            return;
        }
        if(isNaN(ifHasAmount)){
            setSwalAlert('warning','','Enter Valid Amount!');
            return;
        }
        $.ajax({
            url : "{{route('report.update.invoice')}}",
            method : 'POST',
            data : $(this).serialize(),
            success : function (response) {
                console.log(response)
            }
        })
    })


</script>
@endpush


<!-- Modal -->
<div class="modal fade" id="payModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">View Invoice <span id="cusName"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="viewSingleCredit"></div>
                <div id="newPayment"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>