@extends('layouts.master')
@section('title','Supplier')
@section('main_content')
    <div class="row no-gutters">
        <div class="col-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-info d-inline">Manage Supplier</h4>
                    <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#addModal">
                        <i class="fa fa-plus-circle mr-1"></i> Add New Supplier
                    </button>
                </div>
                <div class="card-body">
                    <div class="adv-table">
                        <table class=" display table table-bordered table-striped" id="supplierTable">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Address</th>
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
        //Get Suppliers
        function table_data_row(data) {
            var rows = '';
            var i = 0
            $.each(data ,function (key,value) {
                rows+= '<tr>';
                rows+= '<td>'+ ++i +'</td>';
                rows+= '<td>'+ value.name +'</td>';
                rows+= '<td>'+ value.email +'</td>';
                rows+= '<td>'+ value.phone +'</td>';
                rows+= '<td>'+ value.address +'</td>';
                rows+= '<td data-id="'+value.id+'" class="text-center">';
                rows+= '<a class="btn btn-sm btn-info text-light" id="editRow" data-id="'+value.id+'" data-toggle="modal" data-target="#editModal">Edit</a> ';
                rows+= '<a class="btn btn-sm btn-danger text-light"  id="deleteRow" data-id="'+value.id+'" >Delete</a> ';
                rows+= '</td>';
                rows+= '</tr>';
            });

            $('#supplierBody').html(rows);
        }
        function getAllSuppliers() {
            $.ajax({
                url : <?= json_encode(route('supplier.fetch')) ?>,
                method : 'GET',
                data : {},
                success : function (response) {
                    table_data_row(response)
                }
            })
        }
        getAllSuppliers();

        //Store Supplier
        $('#addSupplierForm').on('submit',function (e) {
            e.preventDefault();
            var name = $('#name').val();
            var email = $('#email').val();
            var phone = $('#phone').val();
            var address = $('#address').val();

            if(name == ''){
                $('#nameError').text('Field Must not be Empty!');
                $('#name').addClass('border-danger');
            }else{
                $('#nameError').text('');
                $('#name').removeClass('border-danger');
            }

            if(email == ''){
                $('#emailError').text('Field Must not be Empty!');
                $('#email').addClass('border-danger');
                flag = false;
            }else{
                $('#emailError').text('');
                $('#email').removeClass('border-danger');
            }

            if(address == ''){
                $('#addressError').text('Field Must not be Empty!');
                $('#address').addClass('border-danger');
            }else{
                $('#addressError').text('');
                $('#address').removeClass('border-danger');
            }

            if(phone == ''){
                $('#phoneError').text('Field Must not be Empty!');
                $('#phone').addClass('border-danger');
            }else{
                $('#phoneError').text('');
                $('#phone').removeClass('border-danger');
            }

            if((name != '') && (email != '') && (address != '') && (phone != '')){
                $.ajax({
                    url : <?= json_encode(route('supplier.store')) ?>,
                    method : 'POST',
                    data : {name : name,email:email,phone:phone,address:address},
                    success:function (response) {
                        if(response.flag == 'INSERT') {
                            setSwalAlert('success', 'Good job!', response.message);
                            $('#addModal').modal('toggle');
                            getAllSuppliers();
                            $("#name").val('');
                            $("#email").val('');
                            $("#address").val('');
                            $("#phone").val('');
                        }else if (response.flag == 'Email_EXIST'){
                            $("#emailError").text(response.message);
                            $('#email').addClass('border-danger');
                        }else if(response.flag == 'Phone_EXIST'){
                            $("#phoneError").text(response.message);
                            $('#phone').addClass('border-danger');
                        }
                    }
                })
            }

        })

        //Delete Supplier
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
                    url : <?= json_encode(route('supplier.delete'))?>,
                    type : 'DELETE',
                    data : {id : id},
                    success : function (response) {
                        getAllSuppliers();
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

        //Edit
        $('body').on('click','#editRow',function (e) {
            e.preventDefault();
            $("#e_phoneError").text('');
            $('#e_phone').removeClass('border-danger');
            $("#e_emailError").text('');
            $('#e_email').removeClass('border-danger');
            var id = $(this).attr('data-id');
            $.ajax({
                url : <?= json_encode(route('supplier.edit'))?>,
                type : 'GET',
                data : {id : id},
                success : function (response) {
                    $('#e_name').val(response.name);
                    $('#e_id').val(response.id);
                    $('#e_address').val(response.address);
                    $('#e_email').val(response.email);
                    $('#e_phone').val(response.phone);
                },
                error : function (e) {
                    console.log(e);
                }
            })

        })

        $('#updateSupplierForm').on('submit',function (e) {
            e.preventDefault();
            $.ajax({
                url : <?= json_encode(route('supplier.update'))?>,
                method:'PUT',
                data: $('#updateSupplierForm').serialize(),
                success:function (response) {
                    if(response.flag == 'UPDATE') {
                        setSwalAlert('success', 'Good job!', response.message);
                        $('#editModal').modal('toggle');
                        getAllSuppliers();
                        $("#e_name").val('');
                        $("#e_email").val('');
                        $("#e_address").val('');
                        $("#e_phone").val('');
                    }else if (response.flag == 'Email_EXIST'){
                        $("#e_emailError").text(response.message);
                        $('#e_email').addClass('border-danger');
                    }else if(response.flag == 'Phone_EXIST'){
                        $("#e_phoneError").text(response.message);
                        $('#e_phone').addClass('border-danger');
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
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Supplier</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addSupplierForm">
                    <div class="form-group">
                        <input type="text" class="form-control" id="name" placeholder="Enter Suppler Name">
                        <span class="text-danger" id="nameError"></span>
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control" id="email" placeholder="Enter Suppler Email">
                        <span class="text-danger" id="emailError"></span>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="phone" placeholder="Enter Suppler Phone">
                        <span class="text-danger" id="phoneError"></span>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="address" placeholder="Enter Suppler Address">
                        <span class="text-danger" id="addressError"></span>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-success btn-block">Add New Supplier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!--Edit  Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Supplier</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="updateSupplierForm">
                    <div class="form-group">
                        <input type="hidden" class="form-control" id="e_id" name="id">
                        <input type="text" name="name" class="form-control" id="e_name" placeholder="Enter Suppler Name">
                        <span class="text-danger" id="nameError"></span>
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control" id="e_email" placeholder="Enter Suppler Email" name="email">
                        <span class="text-danger" id="e_emailError"></span>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="e_phone" placeholder="Enter Suppler Phone" name="phone">
                        <span class="text-danger" id="e_phoneError"></span>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="e_address" placeholder="Enter Suppler Address" name="address">
                        <span class="text-danger" id="addressError"></span>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-success btn-block">Update Supplier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>