@extends('layouts.master')
@section('title','Category')
@section('main_content')
    <div class="row no-gutters">
        <div class="col-12 col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-info">Manage Category</h4>
                </div>
                <div class="card-body">
                    <div class="adv-table">
                        <table class=" display table table-bordered table-striped" id="catTable">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th class="">Actions</th>
                            </tr>

                            <tbody id="catBody"></tbody>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-info">Add New Category</h4>
                </div>
                <div class="card-body">
                    <form id="catFrom">
                        @csrf
                        <label for="">Name</label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="name" placeholder="Category Name" id="cat_name">
                            <span class="text-danger " id="errorText"></span>
                            <div id="response"></div>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-block btn-success"><i class="fa fa-plus"></i> Add Category</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('#catTable').dataTable();
    </script>

    <script>
        function table_data_row(data) {
            var	rows = '';
            var i = 0;
            $.each( data, function( key, value ) {

                rows = rows + '<tr>';
                rows = rows + '<td>'+ ++i +'</td>';
                rows = rows + '<td>'+value.name+'</td>';
                rows = rows + '<td class="text-center">';
                if(value.status == 0){
                    rows = rows + ' <button class="badge badge-danger" data-id="'+value.id+'" id="makeActive">Inactive</button>';
                }else{
                    rows = rows + ' <button class="badge badge-success" data-id="'+value.id+'" id="makeInactive">Active</button>';
                }
                rows+= '</td>';
                rows = rows + '<td data-id="'+value.id+'" class="text-center">';
                rows = rows + '<a class="btn btn-sm btn-info text-light" id="editCat" data-id="'+value.id+'" data-toggle="modal" data-target="#editModal">Edit</a> ';
                rows = rows + '<a class="btn btn-sm btn-danger text-light"  id="deleteCat" data-id="'+value.id+'" >Delete</a> ';
                rows = rows + '</td>';
                rows = rows + '</tr>';
            });
            $("#catBody").html(rows);
        }
        function getAllCategory() {
            $.ajax({
                url: <?= json_encode(route('get.category'))?>,
                type:'GET',
                data: { },
                success: function (data) {
                    table_data_row(data);
                },
                error : function (e) {
                    console.log(e)
                }
            });
        }
        getAllCategory();

        //Store Category
        $('#catFrom').on('submit',function (e) {
            e.preventDefault();
            var name = $("#cat_name").val();
            if(name == ""){
                $('#errorText').text('Field Must not be Empty!');
                $('#cat_name').addClass('border-danger');
                return;
            }
            $.ajax({
                url : <?= json_encode(route('category.store'))?>,
                method:'POST',
                data: {name : name },
                success:function (data) {
                    if(data.message == 'EXIST'){
                        setSwalAlert('error','Sorry..','Category Already Exist!');
                    }else if(data.flag == 'INSERT'){
                        setSwalAlert('success','Good job!',data.message);
                        getAllCategory();
                        $("#cat_name").val('');
                        $('#errorText').text('');
                        $('#cat_name').removeClass('border-danger');
                    }
                },
                error : function (e) {
                    console.log(e)
                }
            });
        });

        //Status Active
        $('body').on('click', '#makeActive', function (event) {
            event.preventDefault();
            var id = $(this).attr('data-id');
            $.ajax(
                {
                    url: <?= json_encode(route('category.status.active'))?>,
                    type: 'GET',
                    data: {
                        id: id
                    },
                    success: function (response){
                        if(response == 'SUCCESS'){
                            getAllCategory();
                        }
                    },
                    error : function (e) {
                        console.log(e);
                    }
                });
            return false;
        });

        //Status Inactive
        $('body').on('click', '#makeInactive', function (event) {
            event.preventDefault();
            var id = $(this).attr('data-id');
            $.ajax(
                {
                    url: <?= json_encode(route('category.status.inactive'))?>,
                    type: 'GET',
                    data: {
                        id: id
                    },
                    success: function (response) {
                        if (response == 'SUCCESS') {
                            getAllCategory();
                            console.log(response);
                        }
                    },
                    error: function (e) {
                        console.log(e);
                    }
                });
            return false;
        });

        //Delete Unit
        $('body').on('click','#deleteCat',function (e) {
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
                    url : <?= json_encode(route('category.destroy'))?>,
                    type : 'DELETE',
                    data : {id : id},
                    success : function (response) {
                        getAllCategory();
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

        //Update Category
        $('body').on('click','#upCat',function (e) {
            e.preventDefault();
            var name = $("#name").val();
            var id = $("#cat_id").val();

            if(name == ''){
                $('#errorUpText').text('Field Must not be Empty!');
                $('#name').addClass('border-danger');
                return;
            }
            $.ajax({
                url : <?= json_encode(route('category.update'))?>,
                method:'POST',
                data: {name : name,id : id },
                success:function (data) {
                    if(data.message == 'EXIST'){
                        setSwalAlert('error','Sorry..','Category Already Exist!');
                        $('#errorUpText').text('');
                        $('#name').removeClass('border-danger');
                    }else if(data.flag == 'UPDATE'){
                        $('#editModal').modal('toggle');
                        setSwalAlert('success','Good job!',data.message);
                        getAllCategory();
                        $('#errorUpText').text('');
                        $('#name').removeClass('border-danger');
                    }
                },
                error : function (e) {
                    console.log(e)
                }
            });
        })
        //Edit Category
        $('body').on('click','#editCat',function (e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            $.ajax({
                url : <?= json_encode(route('category.edit'))?>,
                type : 'GET',
                data : {id : id},
                success : function (response) {
                    $('#name').val(response.name);
                    $('#cat_id').val(response.id);
                },
                error : function (e) {
                    console.log(e);
                }
            })
        });
        {{--//Update Unit--}}
        {{--$('#unitUpdateForm').on('submit',function (e) {--}}
            {{--e.preventDefault();--}}

            {{--console.log(11);--}}
            {{--return;--}}
            {{--var name = $("#name").val();--}}
            {{--var id = $("#unit_id").val();--}}
            {{--$.ajax({--}}
                {{--url : <?= json_encode(route('unit.update'))?>,--}}
                {{--method:'POST',--}}
                {{--data: {name : name,id : id },--}}
                {{--success:function (data) {--}}
                    {{--if(data.message == 'EXIST'){--}}
                        {{--setSwalAlert('error','Sorry..','Unit Already Exist!');--}}
                    {{--}else if(data.flag == 'UPDATE'){--}}
                        {{--setSwalAlert('success','Good job!',data.message);--}}
                        {{--getAllCategory();--}}
                        {{--$('#editModal').modal('toggle');--}}
                    {{--}--}}
                {{--},--}}
                {{--error : function (e) {--}}
                    {{--console.log(e)--}}
                {{--}--}}
            {{--});--}}
        {{--})--}}
    </script>

    {{--Edit Modal--}}
    <div class="modal fade" id="editModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title" id="userCrudModal">Edit Category</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>

                <div class="modal-body">
                    <form id="unitUpdateForm" onsubmit="return false">
                        <input type="hidden" id="cat_id" name="cat_id" value="">
                        <div class="form-group">
                            <input type="text" id="name" name="name" value="" class="form-control">
                            <span class="text-danger " id="errorUpText"></span>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-block btn-info text-light" id="upCat">Update Category</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@stop