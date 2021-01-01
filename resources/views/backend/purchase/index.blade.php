@extends('layouts.master')
@section('title','Purchase')
@section('main_content')
    <div class="row no-gutters">
        <div class="col-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-info d-inline">Manage Purchase</h4>
                    <button type="button" class="btn btn-primary pull-right add_modal" data-toggle="modal" data-target="#addModal">
                        <i class="fa fa-plus-circle mr-1"></i> Add New Purchase
                    </button>
                </div>
                <div class="card-body">
                    <div class="adv-table">
                        <table class=" display table table-bordered table-striped" id="purchaseTable">
                            <thead>
                            <tr>
                                <th>P. No</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Quantity</th>
                                <th>Supplier</th>
                                <th>Status</th>
                                <th class="">Actions</th>
                            </tr>

                            <tbody id="purchaseBody">
<span id="bal"></span>
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
                    console.log(response);
                    table_data_row(response);
                }
            })
        }
        //console.log(getAllPurchase());
        function table_data_row(data) {
            var rows = '';
            $.each(data,function (key,value) {
                console.log(value);
                rows+= '<tr>';
                rows+= '<td>'+ value.purchase_no +'</td>';
                rows+= '<td>'+ value.product.name +'</td>';
                rows+= '<td>'+ value.category.name +'</td>';
                rows+= '<td class="text-center">'+ value.buying_quantity +" {" + value.product_id  +"}" +'</td>';
                rows+= '<td>'+ value.supplier.name +'</td>';
                rows+= '<td class="text-center">';
                if(value.status == 0){
                    rows+= ' <button class="badge badge-danger" data-id="'+value.id+'" id="makeActive">Pending</button>';
                }else{
                    rows+= ' <button class="badge badge-success" data-id="'+value.id+'" id="makeInactive">Approved</button>';
                }
                rows+= '</td>'
                rows+= '<td data-id="'+value.id+'" class="text-center">';
                rows+= '<a class="btn btn-sm btn-info text-light" id="editRow" data-id="'+value.id+'" data-toggle="modal" data-target="#editModal">Edit</a> ';
                rows+= '<a class="btn btn-sm btn-danger text-light"  id="deleteRow" data-id="'+value.id+'" >Delete</a> ';
                rows+= '</td>';
                rows+= '</tr>';

            });
            $('#purchaseBody').html(rows);
            $('#purchaseTable').dataTable();
        }
    </script>

    <script>
        $(function () {
            $.ajax({
                url : <?= json_encode(route('get.suppliers'))?>,
                method : 'GET',
                data : {},
                success :function (response) {
                    var html = '<option value="">Select a Supplier</option>';
                    $.each(response.data,function (key,value) {
                        html+= '<option value="'+value.id+'">'+value.name+'</option>';
                    });
                    $('#supplier_id').html(html);
                    $('#supplierTable').dataTable();
                }
            })
        });

        $('body').on('change','#supplier_id',function () {
            var supplier_id = $(this).val();
            $.ajax({
                url : <?= json_encode(route('get.categories'))?>,
                method : 'GET',
                data : {supplier_id:supplier_id},
                success :function (response) {
                    console.log(response.data);
                    var html = '<option value="">Select a Category</option>';
                    $.each(response.data,function (key,value) {
                        html+= '<option value="'+value.category.id+'">'+value.category.name+'</option>';
                    });
                    $('#category_id').html(html);
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
    </script>
    <script>
        $('body').on('click','#addNewRow',function (e) {
            e.preventDefault();
            calculateTotalAmount();
            var date = $('#date').val();
            var purchase_no = $('#purchase_no').val();
            var supplier_id = $('#supplier_id').val();
            var category_id = $('#category_id').val();
            var categoryName = $('#category_id').find('option:selected').text();
            var product_id = $('#product_id').val();
            var productName = $('#product_id').find('option:selected').text();
            //console.log(date + purchase_no + supplier_id + category_id + categoryName + product_id + productName);

            if(purchase_no == ''){
                setNotifyAlert('Purchase Number field is required!');
                $('.purchase_no_error').addClass('text-danger');
                return false;
            }else{
                $('.purchase_no_error').removeClass('text-danger');
            }
            if(supplier_id == ''){
                setNotifyAlert('Supplier field is required!');
                $('.supplier_id_error').addClass('text-danger');
                return false;
            }else{
                $('.supplier_id_error').removeClass('text-danger');
            }
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
                purchase_no : purchase_no,
                supplier_id : supplier_id,
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

            $('body').on('keyup click','.buying_qty',function () {
                var unit_price = $(this).closest('tr').find('input.unit_price').val();
                var qty = $(this).closest('tr').find('input.buying_qty').val();
                var total = unit_price * qty;
                $(this).closest('tr').find('input.buying_price').val(total);
                calculateTotalAmount();
            });
            $('body').on('keyup click','.unit_price',function () {
                var unit_price = $(this).closest('tr').find('input.unit_price').val();
                var qty = $(this).closest('tr').find('input.buying_qty').val();
                var total = unit_price * qty;
                $(this).closest('tr').find('input.buying_price').val(total);
                calculateTotalAmount();
            });
            function calculateTotalAmount() {
                var sum = 0;
                $('.buying_price').each(function () {
                    var value = $(this).val();
                    if(!isNaN(value) && value.lenght != 0){
                        sum+= parseFloat(value);
                    }
                });
                $('#subTotal').val(sum);
            }
        });

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
    </script>

    <script id="document-template" type="text/x-handlebars-template">
        <tr class="delete_add_more_item" id="delete_add_more_item">
            <input type="hidden" name="date[]" value="@{{ date }}">
            <input type="hidden" name="purchase_no[]" value="@{{ purchase_no }}">
            <input type="hidden" name="supplier_id[]" value="@{{ supplier_id }}">
            <input type="hidden" name="category_id[]" value="@{{ category_id }}">
            <input type="hidden" name="product_id[]" value="@{{ product_id }}">

            <td>@{{category_name}}</td>
            <td>@{{product_name}}</td>
            <td><input type="number" min="1" class="form-control buying_qty" name="buying_qty[]" value="1"></td>
            <td><input type="number" min="1" class="form-control unit_price" name="unit_price[]" value=""></td>
            <td><input type="text" class="form-control" name="description[]"></td>
            <td><input type="text" class="form-control buying_price" name="buying_price[]" value="0"></td>
            <td><button class="btn btn-sm btn-danger remove_row"><i class="fa fa-minus-circle"></i></button></td>
        </tr>

    </script>
@stop


<!--ADD  Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Purchase</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="">Date : </label>
                            <input type="date" class="form-control form-control-sm" name="date" id="date" value="{{date('Y-m-d')}}">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="" class="purchase_no_error">Purchase No : </label>
                            <input type="text" class="form-control form-control-sm" name="purchase_no" id="purchase_no" placeholder="Enter Purchase Number">
                        </div>
                    </div>
                    <div class="col-12 col-md-5">
                        <div class="form-group">
                            <label for="" class="supplier_id_error">Supplier Name : </label>
                            <select name="supplier_id" id="supplier_id" class="form-control select2">
                                <option value="">Select a Supplier</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="" class="category_id_error">Category : </label>
                            <select name="category_id" id="category_id" class="form-control select2">
                                <option value="">Select a Category</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="" class="product_id_error">Product : </label>
                            <select name="product_id" id="product_id" class="form-control select2">
                                <option value="">Select a Product</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                        <div class="form-group">
                            <button class="btn btn-sm btn-success addNewRow" style="margin-top: 28px" id="addNewRow"><i class="fa fa-plus-circle"></i></button>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="card">
                    <form id="addPurchaseForm">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Category</th>
                                <th>Product Name</th>
                                <th width="4%">Quantity</th>
                                <th width="10%">Unit Price</th>
                                <th width="25%">Description</th>
                                <th width="15%">Total</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody id="addRow" class="addRow">
                            <tr id="defaultText">
                                <td colspan="7" class="text-center">Invoice items will be here.</td>
                            </tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="5" class="text-right"><span class="mt-3 d-block">Total</span></th>
                                <td colspan="2"><input type="text" class="form-control" value="0.0" id="subTotal" name="buying_price[]" readonly></td>
                            </tr>
                            <tr>
                                <th colspan="7"><button class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Purchase</button></th>
                            </tr>
                            </tfoot>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>