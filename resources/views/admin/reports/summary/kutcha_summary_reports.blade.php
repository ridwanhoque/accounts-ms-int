@extends('admin.master')
@section('content')

<main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-th-list"></i> Kutcha Stock Information</h1>
          <p>Kutcha Stock information </p>
        </div>
        <ul class="app-breadcrumb breadcrumb side">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item">Kutcha Stock Information</li>
          <li class="breadcrumb-item active"><a href="#">Kutcha Stock Information Table</a></li>
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
            <h3 class="tile-title">Kutcha Stock List </h3>
            
            <div class="tile-body">
                <div class="row">
                    <div class="col-md-12">
                      <form method="GET" action="">
                              {{-- @csrf --}}
                            <table class="table">

                              <tr>
                                <td>
                                    <select name="fm_kutcha_id" id="fm_kutcha_id" class="form-control select2">
                                        <option value="">Choose Kutcha</option>
                                        @foreach ($fm_kutchas as $fm_kutcha)
                                          <option value="{{ $fm_kutcha->id }}" {{ request()->fm_kutcha_id == $fm_kutcha->id ? 'selected':'' }}>{{ $fm_kutcha->raw_material->name ?? '' }} - {{ $fm_kutcha->name }}</option>
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
					          <th>Kutcha Name</th>
                    <th colspan="3">Kutcha Received</th>
                    <th colspan="2">Kutcha Used</th>
                  </tr>
                  <tr>
                    <th></th>
                    <th>Finish Production</th>
                    <th>Sheet Production</th>
                    <th>Direct Production</th>

                    <th>Sheet Production</th>
                    <th>Direct Production</th>
                  </tr>
                </thead>
                <tbody>
                    @isset($fm_kutcha_stocks)
                        @foreach ($fm_kutcha_stocks as $fm_kutcha_stock)
                        @php
                        $sum_kutcha_daily_production = optional($fm_kutcha_stock->sum_kutcha_daily_production)->sum_kutcha ?? 0.00;

                        $sum_kutcha_sheet_production_in = optional($fm_kutcha_stock->sum_kutcha_sheet_production_in)->sum_kutcha ?? 0.00;
                        $sum_kutcha_sheet_production = optional($fm_kutcha_stock->sum_kutcha_sheet_production)->sum_kutcha ?? 0.00;
                        
                        $sum_kutcha_direct_production_in = optional($fm_kutcha_stock->sum_kutcha_direct_production_in)->sum_kutcha ?? 0.00;
                        $sum_kutcha_direct_production = optional($fm_kutcha_stock->sum_kutcha_direct_production)->sum_kutcha ?? 0.00; 

                        @endphp
                        <tr>
                          <td>{{ $fm_kutcha_stock->raw_material->name }} - {{ $fm_kutcha_stock->name ?? '' }}</td>
                    
                          <td>{{ $sum_kutcha_daily_production }}</td>
                          <td>{{ $sum_kutcha_sheet_production }}</td>
                          <td>{{ $sum_kutcha_direct_production }}</td>
                          
                          <td>{{ $sum_kutcha_sheet_production_in }}</td>
                          <td>{{ $sum_kutcha_direct_production_in }}</td>
                          
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
  var fm_kutcha_id = $("#fm_kutcha_id").val();
  var purchase_date_from = $("#purchase_date_from").val();
  var purchase_date_to = $("#purchase_date_to").val();
  var url_data = 'fm_kutcha_id='+fm_kutcha_id+'&purchase_date_from='+purchase_date_from+'&purchase_date_to='+purchase_date_to;
      $.ajax({
      url: "{{ url('reports/fm_kutcha_stocks/filter') }}",
      type: "GET",
      data: {fm_kutcha_id:fm_kutcha_id, purchase_date_from: purchase_date_from, purchase_date_to: purchase_date_to},
      success:function(res){
        $.each(res, function(key, value){
          var total_sold = 0;
          if(value['sales_details'] != null){
            total_sold = value['sales_details']['total_sold'];
          }
          var tr = '<tr>'+
          '<td>'+value['fm_kutcha']['fm_kutcha_code']+'</td>'+
          '<td>'+value['fm_kutcha']['fm_kutcha_id']+'</td>'+
          '<td>'+value['total_purchased']+'</td>'+
          '<td>'+total_sold+'</td>'+
          '<td>'+value['fm_kutcha_stock']['available_quantity']+'</td>'+
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