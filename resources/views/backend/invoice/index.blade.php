@extends('layouts.master')
@section('title','Invoice')
@section('main_content')
    <div class="row no-gutters">
        <div class="col-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-info d-inline">Manage Invoice</h4>
                    <button type="button" class="btn btn-primary pull-right add_modal" data-toggle="modal" data-target="#addModal">
                        <i class="fa fa-plus-circle mr-1"></i> Add New Invoice
                    </button>
                </div>
                <div class="card-body">
                    <div class="adv-table table-responsive">
                        <table class=" display table table-bordered table-striped" id="purchaseTable">
                            <thead>
                            <tr>
                                <th width="1%">SL</th>
                                <th>P. No</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Quantity</th>
                                <th>Supplier</th>
                                <th>Status</th>
                                <th class="">Actions</th>
                            </tr>

                            <tbody id="invoiceBody">
                            </tbody>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        getAllPurchase();
        function getAllPurchase() {
            $.ajax({
                url : <?= json_encode(route('purchase.fetch'))?>,
                method : 'GET',
                data : {},
                success : function (response) {
                  //  console.log(response);
                    table_data_row(response);
                }
            })
        }
        //console.log(getAllPurchase());
        function table_data_row(data) {
            var rows = '';
            var i = 0;
            $.each(data,function (key,value) {
                console.log(value);
                rows+= '<tr>';
                rows+= '<td>'+ ++i +'</td>';
                rows+= '<td>'+ value.purchase_no +'</td>';
                rows+= '<td>'+ value.product.name +'</td>';
                rows+= '<td>'+ value.category.name +'</td>';
                rows+= '<td class="text-center">'+ value.buying_quantity +'</td>';
                rows+= '<td>'+ value.supplier.name +'</td>';
                rows+= '<td class="text-center">';
                if(value.status == 0){
                    rows+= ' <button class="badge badge-danger" data-id="'+value.id+'" id="makeActive">Pending</button>';
                }else{
                    rows+= ' <button class="badge badge-success" data-id="'+value.id+'" id="makeInactive">Approved</button>';
                }
                rows += '</td>'
                rows += '<td class="text-center">';
                if(value.status == 0) {
                    rows += '<a class="btn btn-sm btn-success text-light" id="approvePurchase" data-id="' + value.id + '">Approve</a> ';
                    rows += '<a class="btn btn-sm btn-danger text-light"  id="deleteRow" data-id="' + value.id + '" >Delete</a> ';
                }else{
                    rows += '<a class="btn btn-sm btn-primary text-light"  id="viewRow" data-id="' + value.purchase_no + '" ><i class="fa fa-eye"></i> View</a> ';
                }

                rows += '</td>';
                rows+= '</tr>';

            });
            $('#purchaseBody').html(rows);
            $('#purchaseTable').dataTable();
        }

        $('body').on('click','#approvePurchase',function (e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success mx-2',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            })
            swalWithBootstrapButtons.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Approve it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                $.ajax({
                    url : <?= json_encode(route('approve-purchase'))?>,
                    type : 'PUT',
                    data : {id : id},
                    success : function (response) {
                       // console.log(response);
                        getAllPurchase();
                        swalWithBootstrapButtons.fire(
                            'Approved!',
                            'Your Purchase has been Approved.',
                            'success'
                        )
                    },
                    error : function (e) {
                        console.log(e);
                    }
                })

            } else if (
                    /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
            ) {
                swalWithBootstrapButtons.fire(
                    'Cancelled',
                    'okk :)',
                    'error'
                )
            }
        })
        });

        //Delete Unit
        $('body').on('click','#deleteRow',function (e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success mx-2',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            })

            swalWithBootstrapButtons.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                $.ajax({
                    url : <?= json_encode(route('purchase.destroy'))?>,
                    type : 'DELETE',
                    data : {id : id},
                    success : function (response) {
                        getAllPurchase();
                        swalWithBootstrapButtons.fire(
                            'Deleted!',
                            response.message,
                            'success'
                        )
                    },
                    error : function (e) {
                        console.log(e);
                    }
                })

            } else if (
                    /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
            ) {
                swalWithBootstrapButtons.fire(
                    'Cancelled',
                    'Your file is safe :)',
                    'error'
                )
            }
        })
        })
    </script>

    <script>
        $(function () {
            $.ajax({
                url : <?= json_encode(route('get.categories.invoice'))?>,
                method : 'GET',
                data : {},
                success :function (response) {
                    var html = '<option value="">Select a Category</option>';
                    $.each(response.data,function (key,value) {
                        html+= '<option value="'+value.id+'">'+value.name+'</option>';
                    });
                    $('#category_id').html(html);
                    // $('#supplierTable').dataTable();
                }
            })
        });

        $('body').on('change','#category_id',function () {
            var category_id = $(this).val();
            $.ajax({
                url : <?= json_encode(route('get.purchase.products'))?>,
                method : 'GET',
                data : {category_id:category_id},
                success :function (response) {
                    // console.log(response.data);
                    var html = '<option value="">Select a Product</option>';
                    $.each(response.data,function (key,value) {
                        html+= '<option value="'+value.id+'">'+value.name+'</option>';
                    });
                    $('#product_id').html(html);
                }
            })
        });

        $('body').on('change','#product_id',function () {
            var product_id = $(this).val();
            $.ajax({
                url :<?= json_encode(route('product.quantity'))?>,
                type : 'GET',
                data : {product_id : product_id},
                success:function (response) {
                    $('#product_available').val(response.data);
                }
            })
        })

    </script>
    <script>
        $('body').on('click','#addNewRow',function (e) {
            e.preventDefault();
            calculateTotalAmount();
            var date = $('#date').val();
            var invoice_no = $('#invoice_no').val();
            var supplier_id = $('#supplier_id').val();
            var category_id = $('#category_id').val();
            var categoryName = $('#category_id').find('option:selected').text();
            var product_id = $('#product_id').val();
            var productName = $('#product_id').find('option:selected').text();
            //console.log(date + purchase_no + supplier_id + category_id + categoryName + product_id + productName);

            if(category_id == ''){
                setNotifyAlert('Category field is required!');
                $('.category_id_error').addClass('text-danger');
                return false;
            }else{
                $('.category_id_error').removeClass('text-danger');
            }
            if(product_id == ''){
                setNotifyAlert('Product field is required!');
                $('.product_id_error').addClass('text-danger');
                return false;
            }else{
                $('.product_id_error').removeClass('text-danger');
            }

            var source = $('#document-template').html();
            var template = Handlebars.compile(source);
            var data = {
                date : date,
                invoice_no : invoice_no,
                category_id: category_id,
                category_name : categoryName,
                product_id : product_id,
                product_name : productName
            };

            var html = template(data);
            $('#addRow').append(html);
            $('#defaultText').hide();

            $('body').on('click','.remove_row',function (e) {
                e.preventDefault();
                $(this).closest('.delete_add_more_item').remove();
                calculateTotalAmount();
            });

            $('body').on('keyup click','.selling_qty',function () {
                var unit_price = $(this).closest('tr').find('input.unit_price').val();
                var qty = $(this).closest('tr').find('input.selling_qty').val();
                var total = unit_price * qty;
                $(this).closest('tr').find('input.selling_price').val(total);
                calculateTotalAmount();
                $('#discount').trigger('keyup');
            });
            $('body').on('keyup click','.unit_price',function () {
                var unit_price = $(this).closest('tr').find('input.unit_price').val();
                var qty = $(this).closest('tr').find('input.selling_qty').val();
                var total = unit_price * qty;
                $(this).closest('tr').find('input.selling_price').val(total);
                calculateTotalAmount();
                $('#discount').trigger('keyup');
            });

            $('body').on('keyup','#discount',function () {
                calculateTotalAmount();
            });
            function calculateTotalAmount() {
                var sum = 0;
                $('.selling_price').each(function () {
                    var value = $(this).val();
                    if(!isNaN(value) && value.lenght != 0){
                        sum+= parseFloat(value);
                    }
                });
                var discount_amount = parseFloat($('#discount').val());
                if(!isNaN(discount_amount) && discount_amount.length != 0){
                    sum-=parseFloat(discount_amount);
                }
                $('#subTotal').val(sum);
                var subTotalValueForAlert = $('#subTotal').val();
                if(subTotalValueForAlert< 0){
                    setNotifyAlert('Negative value not allowed!','error');
                    $('#discount').addClass('border-danger');
                }else{
                    $('#discount').removeClass('border-danger');
                }
            }
        });
        $('body').on('click','.add_modal',function () {
            $('#subTotal').val(0.0);
            $('#discount').val(0.0);
        })
        $('#addPurchaseForm').on('submit',function (e) {
            e.preventDefault();
            $.ajax({
                url : <?= json_encode(route('purchase.store'))?>,
                method :'POST',
                data : $(this).serialize(),
                success:function (response) {
                    if(response.flag == 'EMPTY'){
                        setSwalAlert('info','Warning!',response.message);
                    }else if(response.flag == 'INSERT'){
                        setSwalAlert('success','Success!',response.message);
                        $('#addModal').modal('toggle');
                        getAllPurchase();
                        $('#purchase_no').val('');
                        $('#supplier_id').val('');
                        $('#category_id').val('');
                        $('#product_id').val('');
                        $('#addRow').html('');
                        $('#subTotal').val(0.0);
                    }
                },
                error:function (e) {
                    setSwalAlert('error','error!','Data  error!');
                }
            })
        });

//if(<0){
//    setNotifyAlert('success','Negative value not allowed');
//}

    </script>

    <script id="document-template" type="text/x-handlebars-template">
        <tr class="delete_add_more_item" id="delete_add_more_item">
            <input type="hidden" name="date" value="@{{ date }}">
            <input type="hidden" name="invoice_no" value="@{{ invoice_no }}">
            <input type="hidden" name="category_id[]" value="@{{ category_id }}">
            <input type="hidden" name="product_id[]" value="@{{ product_id }}">

            <td>@{{category_name}}</td>
            <td>@{{product_name}}</td>
            <td><input type="number" min="1" class="form-control selling_qty" name="selling_qty[]" value="1"></td>
            <td><input type="number" min="1" class="form-control unit_price" name="unit_price[]" value=""></td>
            <td><input type="text" class="form-control selling_price" name="selling_price[]" value="0" readonly></td>
            <td><button class="btn btn-sm btn-danger remove_row"><i class="fa fa-minus-circle"></i></button></td>
        </tr>
    </script>

    <script>
        $('body').on('change','#payment_status',function () {
            var value = $(this).val();
            if(value == 'partial_paid'){
                $('.partial').show();
            }else{
                $('.partial').hide();
            }
        });

        $(function () {
            $.ajax({
                url : <?= json_encode(route('get.customers.invoice'))?>,
                method : 'GET',
                data : {},
                success :function (response) {
                   // console.log(response.data)
                    var html = '<option value="">Select a Customer</option>';
                    $.each(response.data,function (key,value) {
                        html+= '<option value="'+value.id+'">'+value.name+ " [" + value.phone +"]" +'</option>';
                    });
                    html+= '<option value="add_new_customer">Add New Customer</option>'
                    $('#customer_id').html(html);
                }
            })
        });
        $('body').on('change','#customer_id',function () {
            var value = $(this).val();
            if(value == 'add_new_customer'){
                $('#addNewCustomerInvoice').show();
            }else{
                $('#addNewCustomerInvoice').hide();
            }
        });
    </script>
    <script>
        $('#addInvoiceForm').on('submit',function (e) {
            e.preventDefault();
            $.ajax({
                url : <?= json_encode(route('invoice.store'))?>,
                method : 'POST',
                data : $('#addInvoiceForm').serialize(),
                success : function (response) {
                    console.log(response)
                    if(response.flag == 'EMPTY'){
                        setSwalAlert('error','Sorry!',response.message);
                    }else if(response.flag == 'EMPTY_CUS'){
                        setSwalAlert('info','Sorry!',response.message);
                    }else if(response.flag == 'EMPTY_NAME'){
                        setSwalAlert('info','Sorry!',response.message);
                    }else if(response.flag == 'EMPTY_PAYMENT'){
                        setSwalAlert('info','Sorry!',response.message);
                    }else if(response.flag == 'EMPTY_PAYMENT_PARTIAL'){
                        setSwalAlert('info','Sorry!',response.message);
                    }

                }
            })
        })
    </script>

@stop


<!--ADD  Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Invoice</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row no-gutters">
                    <div class="col-12 col-md-1">
                        <div class="form-group">
                            <label for="">Invoice No : </label>
                            <input type="text" class="form-control form-control-sm" name="invoice_no" id="invoice_no" value="{{$invoice_no}}" readonly="">
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                        <div class="form-group">
                            <label for="">Date : </label>
                            <input type="date" class="form-control form-control-sm" name="date" id="date" value="{{date('Y-m-d')}}">
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                        <div class="form-group">
                            <label for="" class="category_id_error">Category : </label>
                            <select name="category_id" id="category_id" class="form-control form-control-sm select2">
                                <option value="">Select a Category</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="" class="product_id_error">Product : </label>
                            <select name="product_id" id="product_id" class="form-control select2 form-control-sm">
                                <option value="">Select a Product</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-1">
                        <div class="form-group">
                            <label for="">Available : </label>
                            <input type="text" class="form-control form-control-sm"  id="product_available" value="" readonly="">
                        </div>
                    </div>
                    <div class="col-12 col-md-1">
                        <div class="form-group">
                            <button class="btn btn-sm btn-success addNewRow" style="margin-top: 31px" id="addNewRow"><i class="fa fa-plus-circle"></i></button>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="card">
                    {{--//method="post" action="{{route('invoice.store')}}"--}}
                    <form id="addInvoiceForm" >
                        @csrf
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Category</th>
                                <th>Product Name</th>
                                <th width="10%">Quantity</th>
                                <th width="10%">Unit Price</th>
                                <th>Total</th>
                                <th width="10%">Actions</th>
                            </tr>
                            </thead>
                            <tbody id="addRow" class="addRow">
                            <tr id="defaultText">
                                <td colspan="7" class="text-center">Invoice items will be here.</td>
                            </tr>
                            </tbody>
                            <tbody>
                            <tr>
                                <th colspan="4" class="text-right"><span class="mt-3 d-block">Discount</span></th>
                                <td colspan="1"><input type="text" class="form-control" value="0.0" id="discount" name="discount"></td>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-right"><span class="mt-3 d-block">Total</span></th>
                                <td colspan="1"><input type="text" class="form-control" value="0.0" id="subTotal" name="subTotal" readonly></td>
                            </tr>
                            <tr>
                                <td colspan="6">
                                    <div class="form-group">
                                        <textarea name="description" id="description" cols="30" rows="2" class="form-control" placeholder="If have any Description..." ></textarea>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <div class="form-group">
                                        <label for="">Payment : </label>
                                        <select name="payment_status" id="payment_status" class="form-control form-control-sm">
                                            <option value="">Select an Option</option>
                                            <option value="full_paid">Full Paid</option>
                                            <option value="full_due">Full Due</option>
                                            <option value="partial_paid">Partial Payment</option>
                                        </select>
                                        <div class="partial mt-2" style="display: none;">
                                            <input type="text" class="form-control form-control form-control-sm" placeholder="Amount..." name="partial_amount">
                                        </div>
                                    </div>
                                </td>
                                <td colspan="3">
                                    <div class="form-group">
                                        <label for="" id="cusError">Customer : </label>
                                        <select name="customer_id" id="customer_id" class="form-control form-control-sm select2">
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <tr id="addNewCustomerInvoice" style="display: none;">
                                <td colspan="6">
                                    <div class="row">
                                        <div class="col-12 col-md-3">
                                            <input type="text" class="form-control" id="cusName" name="cusName" placeholder="Customer Name">
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <input type="email" class="form-control" id="cusEmail" name="cusEmail" placeholder="Customer Email">
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <input type="text" class="form-control" id="cusPhone" name="cusPhone" placeholder="Customer Phone">
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <input type="text" class="form-control" id="cusAddress" name="cusAddress" placeholder="Customer Address">
                                        </div>
                                    </div>
                                </td>

                            </tr>
                            <tr>
                                <th colspan="1"><button class="btn btn-sm btn-primary btn-block"><i class="fa fa-plus"></i> Invoice</button></th>
                            </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>