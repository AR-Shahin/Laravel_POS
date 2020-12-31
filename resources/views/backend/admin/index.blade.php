@extends('layouts.master')
@section('title','Admin')
@section('main_content')
    <div class="row no-gutters">
        <div class="col-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-info d-inline">Manage Admin</h4>
                    <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#addModal">
                        <i class="fa fa-plus-circle mr-1"></i> Add New Admin
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
                                <th>Image</th>
                                <th>Status</th>
                                <th class="">Actions</th>
                            </tr>

                            <tbody id="AdminBody">
                            </tbody>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        //Get Admin
        function table_data_row(data) {
            var rows = '';
            var i = 0
            //
            $.each(data ,function (key,value) {
                rows+= '<tr>';
                rows+= '<td>'+ ++i +'</td>';
                rows+= '<td>'+ value.name +'</td>';
                rows+= '<td>'+ value.email +'</td>';
                rows+= '<td>'+ value.phone +'</td>';
                rows+= '<td> <img src="../'+value.image+'" alt="" width="100px"></td>';
                rows+= '<td class="text-center">';
                if(value.status == 0){
                    rows+= ' <button class="badge badge-danger" data-id="'+value.id+'" id="makeActive">Inactive</button>';
                }else{
                    rows+= ' <span class="badge badge-success" data-id="'+value.id+'" id="makeInactive">Active</span>';
                }
                rows+= '</td>';
                rows+= '<td data-id="'+value.id+'" class="text-center">';
                rows+= '<a class="btn btn-sm btn-info text-light" id="editRow" data-id="'+value.id+'" data-toggle="modal" data-target="#editModal">Edit</a> ';
                if(<?= Auth::user()->id?> != value.id)
                {
                    rows += '<a class="btn btn-sm btn-danger text-light"  id="deleteRow" data-id="' + value.id + '" >Delete</a> ';
                }
                rows+= '</td>';
                rows+= '</tr>';
            });

            $('#AdminBody').html(rows);
            $('#supplierTable').dataTable();
        }
        function getAllAdmin() {
            $.ajax({
                url : <?= json_encode(route('admin.fetch')) ?>,
                method : 'GET',
                data : {},
                success : function (response) {
                    table_data_row(response);
                    console.log(response)
                }
            })
        }
        getAllAdmin();
        //store
        $('#addAdminForm').on('submit',function (e) {
            e.preventDefault();
            var name = $('#name').val();
            var email = $('#email').val();
            var phone = $('#phone').val();
            var password = $('#password').val();
            var confirm_password = $('#confirm_password').val();
            var role = $('#status').val();
            var image = $('#image').val();

            if((name != '') && (email != '') && (password != '') && (role != '') && (phone != '') && (image != '') && (password == confirm_password)){
                var fdata = new FormData(this);
                $.ajax({
                    url : <?= json_encode(route('admin.store')) ?>,
                    method : 'POST',
                    data :fdata,
                    cache:false,
                    processData:false,
                    contentType:false,
                    success:function (response) {
                        console.log(response);
                        if(response.flag == 'EXT_NOT_MATCH'){
                            setSwalAlert('error',"Extension Doesn't match!",response.message);
                            $('#image').addClass('border-danger');
                        }else if(response.flag == 'EMAIL_NOT_MATCH'){
                            $('#email').addClass('border-danger');
                            $('#emailError').text(response.message);
                        }
                        else if(response.flag == 'BIG_SIZE'){
                            $('#image').addClass('border-danger');
                            $('#imageError').text(response.message);
                        }
                        else if(response.flag == 'INSERT'){
                            setSwalAlert('success', 'Good job!', response.message);
                            getAllAdmin();
                            $('#addModal').modal('toggle');
                            $('#name').val('');
                            $('#email').val('');
                            $('#phone').val('');
                            $('#password').val('');
                            $('#confirm_password').val('');
                            $('#image').val('');
                            $('#role').val('');
                        }
                    }
                })
            }else{
                console.log(0)
            }
        })
        //Delete Admin
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
                    url : <?= json_encode(route('admin.delete'))?>,
                    type : 'DELETE',
                    data : {id : id},
                    success : function (response) {
                        getAllAdmin();
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

@stop

<!--ADD  Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Admin</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addAdminForm" enctype="multipart/form-data">
                    <div class="form-group">
                        <input type="text" class="form-control" id="name" placeholder="Enter Admin Name" name="name">
                        <span class="text-danger" id="nameError"></span>
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control" id="email" placeholder="Enter Admin Email" name="email">
                        <span class="text-danger" id="emailError"></span>
                    </div>
                    <div class="form-group">
                        <input type="file" class="form-control" id="image" name="image">
                        <span class="text-danger" id="imageError"></span>
                    </div>
                    <div class="row no-gutters">
                        <div class="col-7">
                            <div class="form-group">
                                <input type="text" name="phone" class="form-control" id="phone" placeholder="Enter Admin Phone">
                                <span class="text-danger" id="phoneError"></span>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="form-group">
                                <select name="status" id="status" class="form-control">
                                    <option value="">Select Role</option>
                                    <option value="1">Admin</option>
                                    <option value="0">Operator</option>
                                </select>
                                <span class="text-danger" id="statusError"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row no-gutters">
                        <div class="col-6">
                            <div class="form-group">
                                <input type="password" class="form-control" id="password" placeholder="Enter Admin Password" name="password">
                                <span class="text-danger" id="passwordError"></span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <input type="password" class="form-control" id="confirm_password" placeholder="Enter Confirm Password">
                                <span class="text-danger" id="confirm_passwordError"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-success btn-block">Add New Admin</button>
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