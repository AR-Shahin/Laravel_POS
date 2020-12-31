@extends('layouts.master')
@section('title','Purchase')
@section('main_content')
    <div class="row no-gutters">
        <div class="col-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-info d-inline">Manage Purchase</h4>
                    <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#addModal">
                        <i class="fa fa-plus-circle mr-1"></i> Add New Purchase
                    </button>
                </div>
                <div class="card-body">
                    <div class="adv-table">
                        <table class=" display table table-bordered table-striped" id="supplierTable">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Supplier</th>
                                <th>Unit</th>
                                <th class="">Actions</th>
                            </tr>

                            <tbody id="supplierBody">

                            </tbody>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#supplierTable').dataTable();
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
//            console.log('cat ' + category_id);
            $.ajax({
                url : <?= json_encode(route('get.purchase.products'))?>,
                method : 'GET',
                data : {category_id:category_id},
                success :function (response) {
                    console.log(response.data);
                    var html = '<option value="">Select a Product</option>';
                    $.each(response.data,function (key,value) {
                        html+= '<option value="'+value.id+'">'+value.name+'</option>';
                    });
                    $('#product_id').html(html);
                }
            })
        });
    </script>
@stop


<!--ADD  Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
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
                           <input type="date" class="form-control" name="date" id="date" value="{{date('Y-m-d')}}">
                       </div>
                   </div>
                   <div class="col-12 col-md-4">
                       <div class="form-group">
                           <label for="">Purchase No : </label>
                           <input type="text" class="form-control" name="purchase_no" id="purchase_no">
                       </div>
                   </div>
                   <div class="col-12 col-md-5">
                       <div class="form-group">
                           <label for="">Supplier Name : </label>
                           <select name="supplier_id" id="supplier_id" class="form-control">
                               <option value="">Select a Supplier</option>
                           </select>
                       </div>
                   </div>
               </div>

                <div class="row">
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="">Category : </label>
                            <select name="category_id" id="category_id" class="form-control">
                                <option value="">Select a Category</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="">Product : </label>
                            <select name="product_id" id="product_id" class="form-control">
                                <option value="">Select a Product</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                        <div class="form-group">
                          <button class="btn btn-sm btn-success" style="margin-top: 35px"><i class="fa fa-plus-circle"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>