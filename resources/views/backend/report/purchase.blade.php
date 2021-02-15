@extends('layouts.master')
@section('title','Purchase Report')
@section('main_content')
    <div class="row no-gutters">
        <div class="col-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-4">
                            <h3 class="mb-0 text-primary" style="display: inline-block" >Purchase Report</h3>
                        </div>
                        <div class="col-8">

                            <form action="" method="get" id="purchaseReportForm">

                                <div class="row no-gutters">
                                    <div class="col-4">
                                        <input type="date" class="form-control" name="start_date" id="start_date" value="@php echo date('Y-m-d') @endphp">
                                    </div>

                                    <div class="col-4">
                                        <input type="date" class="form-control" name="end_date" id="end_date" value="@php echo date('Y-m-d') @endphp">
                                    </div>
                                    <div class="col-4">
                                        <button class="btn btn-info"><i class="fa fa-search"></i> Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body" style="display: none" id="cardBody">
                    <h6 class="text-primary">Purchase Report From <b><span id="startDate"></span></b> to <b><span id="endDate"></span></b></h6>
                    <hr>
                    <div class="adv-table">
                        <table class=" display table  table-striped" id="purchaseReportTable_">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Supplier</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>

                            <tbody id="purchaseReportTable">

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
        var qty = 0;
        var total = 0;
        $.each(data ,function (key,value) {
            rows+= '<tr>';
            rows+= '<td>'+ value.date +'</td>';
            rows+= '<td> '+ value.product.name +'</td>';
            rows+= '<td> '+ value.category.name +'</td>';
            rows+= '<td> '+ value.supplier.name +'</td>';
            rows+= '<td> '+ value.buying_quantity +'</td>';
            rows+= '<td> '+ value.unit_price +'</td>';
            rows+= '<td> '+ value.buying_price +'</td>';
            rows+= '</tr>';
            qty += value.buying_quantity;
            total += value.buying_price;
        });
        rows += '<tr><th colspan="4" class="text-right">Total : </th>';
        rows += '<td>'+qty+'</td>';
        rows += '<th>Total</td>';
        rows += '<td>'+total+'</td>';
        rows += '</tr>';


        $('#purchaseReportTable').html(rows);
    }
  $('body').on('submit','#purchaseReportForm',function (e) {
      e.preventDefault();
      var start = $('#start_date').val();
      var end = $('#end_date').val();
      $.ajax({
          url : "{{route('report.purchase.date')}}",
          method : 'get',
          data : {start_date:start, end_date:end},
          success : function (response) {
              $('#cardBody').show();
              $('#startDate').text(start);

              $('#endDate').text(end);
            // console.log(response);
              table_data_row(response);
          }
      })
  });

    function getTodaysPurchaseReports() {
        var start = $('#start_date').val();
        var end = $('#end_date').val();
        $.ajax({
            url : "{{route('report.purchase.date')}}",
            method : 'get',
            data : {start_date:start, end_date:end},
            success : function (response) {
                $('#cardBody').show();
                $('#startDate').text(start);

                $('#endDate').text(end);
                // console.log(response);
                table_data_row(response);
            }
        })
    }
    getTodaysPurchaseReports();

</script>
@endpush


