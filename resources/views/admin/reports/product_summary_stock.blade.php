@extends('admin.master')
@section('content')

<main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-th-list"></i> Product Stock Information</h1>
          <p>Product Stock information </p>
        </div>
        <ul class="app-breadcrumb breadcrumb side">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item">Product Stock Information</li>
          <li class="breadcrumb-item active"><a href="#">Product Stock Information Table</a></li>
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
            <h3 class="tile-title">Product Stock List </h3>
            
            <div class="tile-body">
                <div class="row">
                    <div class="col-md-12">
                      <form method="GET" action="">
                              {{-- @csrf --}}
                            <table class="table">

                              <tr>
                                <td>
                                    <select name="product_id" id="product_id" class="form-control select2">
                                        <option value="">Choose Product</option>
                                        @foreach ($products as $product)
                                          <option value="{{ $product->id }}" {{ request()->product_id == $product->id ? 'selected':'' }}>{{ $product->raw_material->name ?? '' }} - {{ $product->name }}</option>
                                        @endforeach
                                      </select>
                                </td>
                                <td>
                                  <input type="text" name="from_date" id="from_date" class="form-control dateField" value="{{ request()->from_date }}" placeholder="{{ Carbon\Carbon::today()->toDateString() }}">
                                </td>
                                <td>
                                  <input type="text" name="to_date" id="to_date" class="form-control dateField" value="{{ request()->to_date }}" placeholder="{{ Carbon\Carbon::today()->toDateString() }}">
                                </td>
                                <td>
                                  <button type="submit" id="submitBtn" class="btn btn-primary"><i class="fa fa-check"></i></button>
                                </td>
                              </tr>

                            </table>
                          </form>
                    </div>
                    
                  </div>
                  <div class="mt-4"></div>
              <table class="table table-bordered text-center print-area" id="stock_table">
                <thead>
                  <tr>
					          <th>Product Name</th>
                    <th colspan="3">Production</th>
                    <th colspan="3">Transfer</th>
                    <th colspan="3">Sale</th>
                    {{-- <th colspan="3">Available</th> --}}
                  </tr>
                  <tr>
                    <th></th>
                    <!-- <th>finish/direct kg</th> -->
                    <th>piece</th>
                    <th>pack</th>
                    <th>kg</th>

                    <th>piece</th>
                    <th>pack</th>
                    <th>kg</th>
                    
                    <th>piece</th>
                    <th>pack</th>
                    <th>kg</th>

                    {{-- <th>piece</th>
                    <th>pack</th>
                    <th>kg</th> --}}
                  </tr>
                </thead>
                <tbody>
                  {{-- @dd($product_stocks->first()) --}}
                    @isset($production_stock)
                        @foreach ($production_stock as $product_stock)
                        @php
                        $daily_pcs = $product_stock->daily_production_sum->first()->qty_pcs ?? 0;
                        $direct_pcs = $product_stock->direct_production_sum->first()->qty_pcs ?? 0;
                        $daily_packs = $product_stock->daily_production_sum->first()->qty_packs ?? 0;
                        $direct_packs = $product_stock->direct_production_sum->first()->qty_packs ?? 0;
                        $daily_weight = $product_stock->daily_production_sum->first()->qty_weight ?? 0;
                        $direct_weight = $product_stock->direct_production_sum->first()->qty_weight ?? 0;
                        $transfer_pcs = $product_stock->stock_transfer_sum->first()->qty_pcs ?? 0;
                        $delivery_pcs = $product_stock->product_delivery_sum->first()->qty_pcs ?? 0;
                        $transfer_packs = $product_stock->stock_transfer_sum->first()->qty_packs ?? 0;
                        $delivery_packs = $product_stock->product_delivery_sum->first()->qty_packs ?? 0;
                        $transfer_weight = $product_stock->stock_transfer_sum->first()->qty_weight ?? 0;
                        $delivery_weight = $product_stock->product_delivery_sum->first()->qty_weight ?? 0;
                        @endphp
                        <tr>
                          <td>{{ $product_stock->raw_material->name ?? '' }} - {{ $product_stock->name ?? '' }}</td>
                    
                          <td>{{ $daily_pcs }}</td>
                          <td>{{ $daily_packs }}</td>
                          <td>{{ $daily_weight }}</td>
                    
                          <td>{{ $transfer_pcs }}</td>
                          <td>{{ $transfer_packs }}</td>
                          <td>{{ $transfer_weight }}</td>
                    
                          <td>{{ $delivery_pcs }}</td>
                          <td>{{ $delivery_packs }}</td>
                          <td>{{ $delivery_weight }}</td>
                          
                          {{-- <td>{{ $product_stock['available_quantity'] }}</td>
                          <td>{{ $product_stock['available_pack'] }}</td>
                          <td>{{ $product_stock['available_weight'] }}</td> --}}
                         </tr>
                        @endforeach
                    @endisset
                      
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

      // $('#stock_table').dataTable();
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