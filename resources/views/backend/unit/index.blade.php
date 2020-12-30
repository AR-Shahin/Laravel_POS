@extends('layouts.master')
@section('title','Unit')
@section('main_content')
    <div class="row no-gutters">
        <div class="col-sm-12 col-md-8">
            <section class="card">
                <header class="card-header">
                    <h4 class="text-primary">Manage Unit</h4>
                    <div class="adv-table">
                        <table class="display table table-bordered table-striped" id="unit_table">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th class="">Actions</th>
                            </tr>
                            <tbody id="unitTable"></tbody>
                            </thead>
                        </table>
                    </div>
                </header>
            </section>
        </div>
        <div class="col-12 col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-primary">Add Unit</h4>
                </div>
                <div class="card-body">
                    <form id="UnitForm">
                        @csrf
                        <label for="">Name</label>
                        <div class="form-group">
                            <input type="text" class="form-control" name="name" placeholder="Unit Name" id="unit_name">
                            <span class="text-danger">{{($errors->has('name')) ? ($errors->first('name')) : ' '}}</span>
                            <div id="response"></div>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-block btn-success"><i class="fa fa-plus"></i> Add Unit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        var get_url = <?php echo json_encode(route('get.unit')) ?>;
        var store_unit_url = <?php echo json_encode(route('unit.store')) ?>;
        var active_unit_url = <?php echo json_encode(route('unit.status.active')) ?>;
    </script>
    <script>
        $(document).ready(function () {
            $('#unit_table').dataTable( {
                "aaSorting": [[ 4, "desc" ]]
            } );

            //Fetch Unit
            getAllUnits();
            function getAllUnits() {
                $.ajax({
                    url: get_url,
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
                    rows = rows + '<a class="btn btn-sm btn-info text-light" id="editUnit" data-id="'+value.id+'" data-toggle="modal" data-target="#editModal">Edit</a> ';
                    rows = rows + '<a class="btn btn-sm btn-danger text-light"  id="deleteUnit" data-id="'+value.id+'" >Delete</a> ';
                    rows = rows + '</td>';
                    rows = rows + '</tr>';
                });
                $("#unitTable").html(rows);
            }

            //Store Unit
            $('#UnitForm').on('submit',function (e) {
                e.preventDefault();
                var name = $("#unit_name").val();
                var  URL = store_unit_url;
                $.ajax({
                    url : URL,
                    method:'POST',
                    data: {name : name },
                    success:function (data) {
                        console.log(data);
                        if(data.message == 'EXIST'){
                            setSwalAlert('error','Sorry..','Category Already Exist!');
                        }else if(data.flag == 'INSERT'){
                            setSwalAlert('success','Good job!',data.message);
                            getAllUnits();
                            $("#unit_name").val('');
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
                        url: active_unit_url,
                        type: 'GET',
                        data: {
                            id: id
                        },
                        success: function (response){
                            if(response == 'SUCCESS'){
                                getAllUnits();
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
                        url: <?= json_encode(route('unit.status.inactive')) ?>,
                        type: 'GET',
                        data: {
                            id: id
                        },
                        success: function (response) {
                            if (response == 'SUCCESS') {
                                getAllUnits();
                            }
                        },
                        error: function (e) {
                            console.log(e);
                        }
                    });
                return false;
            })

            //Edit Unit
            $('body').on('click','#editUnit',function (e) {
                e.preventDefault();
                var id = $(this).attr('data-id');
                $.ajax({
                    url : <?= json_encode(route('unit.edit'))?>,
                    type : 'GET',
                    data : {id : id},
                    success : function (response) {
                        $('#name').val(response.name);
                        $('#unit_id').val(response.id);
                    },
                    error : function (e) {
                        console.log(e);
                    }
                })
            });

            //Update Unit
            $('#unitUpdateForm').on('submit',function (e) {
                e.preventDefault();
                var name = $("#name").val();
                var id = $("#unit_id").val();
                $.ajax({
                    url : <?= json_encode(route('unit.update'))?>,
                    method:'POST',
                    data: {name : name,id : id },
                    success:function (data) {
                        if(data.message == 'EXIST'){
                            setSwalAlert('error','Sorry..','Unit Already Exist!');
                        }else if(data.flag == 'UPDATE'){
                            setSwalAlert('success','Good job!',data.message);
                            getAllUnits();
                            $('#editModal').modal('toggle');
                        }
                    },
                    error : function (e) {
                        console.log(e)
                    }
                });
            })

            //Delete Unit
            $('body').on('click','#deleteUnit',function (e) {
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
                        url : <?= json_encode(route('unit.destroy'))?>,
                        type : 'DELETE',
                        data : {id : id},
                        success : function (response) {
                            getAllUnits();
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
        });
    </script>

    {{--Edit Modal--}}
    <div class="modal fade" id="editModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title" id="userCrudModal">Edit Unit</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>

                <div class="modal-body">
                    <form id="unitUpdateForm" >
                        <input type="hidden" id="unit_id" name="unit_id" value="">
                        <div class="form-group">
                            <input type="text" id="name" name="name" value="" class="form-control">
                        </div>
                        <div class="form-group">
                            <button class="btn btn-block btn-info text-light">Update Unit</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@stop