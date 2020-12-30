@extends('layouts.master')
@section('title','Product')
@section('main_content')
    <div class="row no-gutters">
        <div class="col-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-info d-inline">Manage Product</h4>
                    <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#addModal">
                        <i class="fa fa-plus-circle mr-1"></i> Add New Product
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
                                <th>Quantity</th>
                                <th>Supplier</th>
                                <th>Added By</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                            </tr>

                            <tbody id="productBody">

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
        //Fetch
        getAllProduct();
        function getAllProduct() {
            $.ajax({
                url : <?= json_encode(route('product.fetch')) ?>,
                method : 'GET',
                data : {},
                success : function (response) {
                    console.log(response);
                    table_data_row(response)
                }
            })
        }
        function table_data_row(data) {
            var rows = '';
            var i = 0;
            $.each(data,function (key,value) {
                rows+= '<tr>';
                rows+= '<td>'+ ++i +'</td>';
                rows+= '<td>'+ value.name +'</td>';
                rows+= '<td>'+ value.category.name +'</td>';
                rows+= '<td class="text-center">'+ value.quantity +' ('+value.unit.name +')' +'</td>';
                rows+= '<td>'+ value.supplier.name +'</td>';
                rows+= '<td>'+ value.user.name +'</td>';
                rows+= '<td class="text-center">';
                if(value.status == 0){
                    rows+= ' <button class="badge badge-danger" data-id="'+value.id+'" id="makeActive">Inactive</button>';
                }else{
                    rows+= ' <button class="badge badge-success" data-id="'+value.id+'" id="makeInactive">Active</button>';
                }
                rows+= '</td>'
                rows+= '<td data-id="'+value.id+'" class="text-center">';
                rows+= '<a class="btn btn-sm btn-info text-light" id="editRow" data-id="'+value.id+'" data-toggle="modal" data-target="#editModal">Edit</a> ';
                rows+= '<a class="btn btn-sm btn-danger text-light"  id="deleteRow" data-id="'+value.id+'" >Delete</a> ';
                rows+= '</td>';
                rows+= '</tr>';

            });
            $('#productBody').html(rows);

        }
        //Store
        $('#addProductForm').on('submit',function (e) {
            e.preventDefault();
            var category = $('#category_id').val();
            var unit = $('#unit_id').val();
            var supplier = $('#supplier_id').val();
            var name = $('#product').val();

            if((name != '') && (category != '') && (unit != '') && (supplier != '')){
                $.ajax({
                    url : <?= json_encode(route('product.store')) ?>,
                    method : 'POST',
                    data : $(this).serialize(),
                    success : function (response) {
                        if(response.flag == 'INSERT') {
                            setSwalAlert('success', 'Good job!', response.message);
                            $('#addModal').modal('toggle');
                            getAllProduct();
                            $("#product").val('');
                            $("#supplier_id").val('');
                            $("#category_id").val('');
                            $("#unit_id").val('');
                        }else if (response.flag == 'Product_EXIST'){
                            $("#productError").text(response.message);
                            $('#product').addClass('border-danger');
                        }
                    }
                })
            }
        });

        //Status Inactive
        $('body').on('click', '#makeInactive', function (event) {
            event.preventDefault();
            var id = $(this).attr('data-id');

            $.ajax(
                {
                    url: <?= json_encode(route('product.status.inactive'))?>,
                    type: 'PUT',
                    data: {
                        id: id
                    },
                    success: function (response) {
                        if (response == 'SUCCESS') {
                            getAllProduct();
                            console.log(response);
                        }
                    },
                    error: function (e) {
                        console.log(e);
                    }
                });
            return false;
        });

        //Status Active
        $('body').on('click', '#makeActive', function (event) {
            event.preventDefault();
            var id = $(this).attr('data-id');
            $.ajax(
                {
                    url: <?= json_encode(route('product.status.active'))?>,
                    type: 'PUT',
                    data: {
                        id: id
                    },
                    success: function (response){
                        if(response == 'SUCCESS'){
                            getAllProduct();
                        }
                    },
                    error : function (e) {
                        console.log(e);
                    }
                });
            return false;
        });

        //Delete
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
                    url : <?= json_encode(route('product.delete'))?>,
                    type : 'DELETE',
                    data : {id : id},
                    success : function (response) {
                        getAllProduct();
                        swalWithBootstrapButtons.fire(
                            'Deleted!',
                            'Your file has been deleted.',
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

        //Edit
        $('body').delegate('#editRow','click',function (e) {
            e.preventDefault();
            $("#e_productError").text('');
            $('#e_product').removeClass('border-danger');
            var id = $(this).attr('data-id');
            $.ajax({
                url : <?= json_encode(route('product.edit'))?>,
                type : 'GET',
                data : {id : id},
                success : function (response) {
                    $('#e_id').val(response.id);
                    $('#e_product').val(response.name);
                    $('#e_supplier_id').val(response.supplier.id);
                    $('#e_category_id').val(response.category.id);
                    $('#e_unit_id').val(response.unit.id);
                },
                error : function (e) {
                    console.log(e);
                }
            })

        })

        $('#updateProductForm').on('submit',function (e) {
            e.preventDefault();
            $.ajax({
                url : <?= json_encode(route('product.update'))?>,
                method:'PUT',
                data: $('#updateProductForm').serialize(),
                success:function (response) {
                    if(response.flag == 'UPDATE') {
                        setSwalAlert('success', 'Good job!', response.message);
                        $('#editModal').modal('toggle');
                        getAllProduct();
                        $("#e_product").val('');
                    }
                },
                error : function (e) {
                    console.log(e)
                }
            });
        })

    </script>
@stop



<!--ADD  Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addProductForm">
                    <div class="form-group">
                        <select name="supplier_id" id="supplier_id" class="form-control">
                            <option value="">Select Supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                            @endforeach
                        </select>
                        <span class="text-danger" id="supplierError"></span>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <select name="category_id" id="category_id" class="form-control">
                                    <option value="">Select Category</option>
                                    @foreach($cats as $cat)
                                        <option value="{{$cat->id}}">{{$cat->name}}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger" id="categoryError"></span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <select name="unit_id" id="unit_id" class="form-control">
                                    <option value="">Select Unit</option>
                                    @foreach($units as $unit)
                                        <option value="{{$unit->id}}">{{$unit->name}}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger" id="unitError"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="product" placeholder="Enter Product Name" name="name">
                        <span class="text-danger" id="productError"></span>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-success btn-block">Add New Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!--Edit  Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="updateProductForm">
                    <div class="form-group">
                        <select name="supplier_id" id="e_supplier_id" class="form-control">
                            <option value="">Select Supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                            @endforeach
                        </select>
                        <span class="text-danger" id="e_supplierError"></span>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <select name="category_id" id="e_category_id" class="form-control">
                                    <option value="">Select Category</option>
                                    @foreach($cats as $cat)
                                        <option value="{{$cat->id}}">{{$cat->name}}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger" id="e_categoryError"></span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <select name="unit_id" id="e_unit_id" class="form-control">
                                    <option value="">Select Unit</option>
                                    @foreach($units as $unit)
                                        <option value="{{$unit->id}}">{{$unit->name}}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger" id="e_unitError"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="e_product" placeholder="Enter Product Name" name="name">
                        <input type="hidden" name="id" id="e_id">
                        <span class="text-danger" id="e_productError"></span>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-success btn-block">Add New Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>