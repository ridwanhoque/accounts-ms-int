@extends('admin.master')
@section('content')

<main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-th-list"></i> Product Branch Stock Information</h1>
          <p>Product Branch Stock information </p>
        </div>
        <ul class="app-breadcrumb breadcrumb side">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item">Product Branch Stock Information</li>
          <li class="breadcrumb-item active"><a href="#">Product Branch Stock Information Table</a></li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
            @if(Session::get('message'))
            <div class="alert alert-success">
              {{ Session::get('message') }}
            </div>
            @endif
          
          <div class="tile">
            <h3 class="tile-title">Product Branch Stock List </h3>
            
            <div class="tile-body">
                {{-- <div class="row">
                    <div class="col-md-12">
                      <div class="card">
                        <div class="card-body">
                          <form method="POST" action="">
                              @csrf
                            <table class="table">
                              <tr>
                                <th>Product Name</th>
                                <th> From Date </th>
                                <th colspan="2"> To Date </th>
                              </tr>

                              <tr>
                                <td>
                                    <select name="product_id" id="product_id" class="form-control select2">
                                        @foreach ($products as $product)
                                          <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                      </select>
                                </td>
                                <td>
                                    <input type="text" name="purchase_date_from" id="purchase_date_from" class="form-control dateField">
                                </td>
                                <td>
                                    <input type="text" name="purchase_date_to" id="purchase_date_to" class="form-control dateField">
                                </td>
                                <td>
                                  <button type="button" id="submitBtn" class="btn btn-primary"><i class="fa fa-check"></i></button>
                                </td>
                              </tr>

                            </table>
                          </form>
                        </div>
                      </div>  
                    </div>
                    
                  </div> --}}
                  <div class="mt-4"></div>
              <table class="table table-bordered text-center print-area" id="stock_table">
                <thead>
                  <tr>
                    <th rowspan="2">Branch</th>
                    <th rowspan="2">Product Name</th>
                    <th colspan="3">Transferred</th>
                    <th colspan="3">Sold</th>
                    <th colspan="3">Available</th>
                  </tr>
                  <tr>
                    <th>Pcs</th>
                    <th>Pack</th>
                    <th>Weight</th>
                    
                    <th>Pcs</th>
                    <th>Pack</th>
                    <th>Weight</th>
                    
                    <th>Pcs</th>
                    <th>Pack</th>
                    <th>Weight</th>
                  </tr>
                </thead>
                <tbody>
                        @foreach ($product_branch_stocks as $product_branch_stock)
                        <tr>
                            <td>{{ $product_branch_stock->branch->name }}</td>
                            <td>{{ $product_branch_stock->product->name }}</td>

                            <td>{{ $product_branch_stock->transferred_quantity }}</td>
                            <td>{{ $product_branch_stock->transferred_pack }}</td>
                            <td>{{ $product_branch_stock->transferred_weight }}</td>

                            <td>{{ $product_branch_stock->sold_quantity }}</td>
                            <td>{{ $product_branch_stock->sold_pack }}</td>
                            <td>{{ $product_branch_stock->sold_weight }}</td>

                            <td>{{ $product_branch_stock->available_quantity }}</td>
                            <td>{{ $product_branch_stock->available_pack }}</td>
                            <td>{{ $product_branch_stock->available_weight }}</td>
                        </tr>
                        @endforeach
                      
                </tbody>
              </table>
            </div>

            
          <div class="row d-print-none mt-2">
            <div class="col-12 text-right"><a class="btn btn-primary" href="javascript:window.print();"><i class="fa fa-print"></i> Print</a></div>
          </div>
  

          </div>
        </div>
      </div>
    </main>


    <script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>
    <!-- Data table plugin-->
    <script type="text/javascript" src="{{ asset('assets/admin/js/plugins/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/admin/js/plugins/dataTables.bootstrap.min.js')}}"></script>
     
    <script  src="{{ asset('assets/admin/js/plugins/select2.min.js')}}"></script>

    
    <script type="text/javascript">
      $('.select2').select2();

      $('#stock_table').dataTable();
    </script>

    <script>

$("#submitBtn").on('click', function(){

  $('#stock_table tbody').empty();
  var product_id = $("#product_id").val();
  var purchase_date_from = $("#purchase_date_from").val();
  var purchase_date_to = $("#purchase_date_to").val();
  var url_data = 'product_id='+product_id+'&purchase_date_from='+purchase_date_from+'&purchase_date_to='+purchase_date_to;
      $.ajax({
      url: "{{ url('reports/product_stocks/filter') }}",
      type: "GET",
      data: {product_id:product_id, purchase_date_from: purchase_date_from, purchase_date_to: purchase_date_to},
      success:function(res){
        $.each(res, function(key, value){
          var total_sold = 0;
          if(value['sales_details'] != null){
            total_sold = value['sales_details']['total_sold'];
          }
          var tr = '<tr>'+
          '<td>'+value['product']['product_code']+'</td>'+
          '<td>'+value['product']['product_id']+'</td>'+
          '<td>'+value['total_purchased']+'</td>'+
          '<td>'+total_sold+'</td>'+
          '<td>'+value['product_stock']['available_quantity']+'</td>'+
          '</tr>';
          $('#stock_table tbody').append(tr);
        });
      }

      });

  

    });


   /*
   function totalAmount() {
            var total = 0;
            $('.service-prices').each(function (i, price) {
                var p = $(price).val();
                total += p ? parseFloat(p) : 0;
            });
            var subtotal = $('#subTotal').val(total);
            discountAmount();
        }

   */
    
    </script>
  @include('admin.includes.date_field')

  @include('admin.includes.delete_confirm')

    @endsection