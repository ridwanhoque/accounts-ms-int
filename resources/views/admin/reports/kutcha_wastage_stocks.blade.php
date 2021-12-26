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
                {{-- <div class="row">
                    <div class="col-md-12">
                      <div class="card">
                        <div class="card-body">
                          <form method="POST" action="">
                              @csrf
                            <table class="table">
                              <tr>
                                <th>Kutcha Name</th>
                                <th> From Date </th>
                                <th colspan="2"> To Date </th>
                              </tr>

                              <tr>
                                <td>
                                    <select name="sheet_wastage_id" id="sheet_wastage_id" class="form-control select2">
                                        @foreach ($sheet_wastages as $sheet_wastage)
                                          <option value="{{ $sheet_wastage->id }}">{{ $sheet_wastage->name }}</option>
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
              <table class="table table-bordered" id="stock_table">
                <thead>
                  <tr>
                    <th>Raw Material Name</th>
                    <th>Kutcha Opening (kg)</th>
                    <th>Kutcha Received (kg)</th>
                    <th>Kutcha Used (kg)</th>
                    <th>Kutcha Available (kg)</th>
                  </tr>
                </thead>
                <tbody>
                    @isset($kutcha_wastage_stocks)
                        @foreach ($kutcha_wastage_stocks as $wastage_stock)
                        <tr>
                          <td>{{ $wastage_stock->fm_kutcha->raw_material->name.' - '.$wastage_stock->fm_kutcha->name }}</td>
                          <td>{{ $wastage_stock->fm_kutcha->fm_kutcha_opening['opening_kg'] }}</td>
                          <td>{{ $wastage_stock['total_kg'] ?: '0.00' }} {{ ' '.config('app.kg') }}</td>
                          <td>{{ $wastage_stock['used_kg'] ?: '0.00' }} {{ ' '.config('app.kg') }}</td>
                          <td>{{ $wastage_stock['available_kg'] ?: '0.00' }} {{ ' '.config('app.kg') }}</td>
                        </tr>
                        @endforeach
                    @endisset
                      
                </tbody>
              </table>
              {{ $kutcha_wastage_stocks->links() }}
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
    </script>

    <script>

$("#submitBtn").on('click', function(){

  $('#stock_table tbody').empty();
  var sheet_wastage_id = $("#sheet_wastage_id").val();
  var purchase_date_from = $("#purchase_date_from").val();
  var purchase_date_to = $("#purchase_date_to").val();
  var url_data = 'sheet_wastage_id='+sheet_wastage_id+'&purchase_date_from='+purchase_date_from+'&purchase_date_to='+purchase_date_to;
      $.ajax({
      url: "{{ url('reports/kutcha_wastage_stocks/filter') }}",
      type: "GET",
      data: {sheet_wastage_id:sheet_wastage_id, purchase_date_from: purchase_date_from, purchase_date_to: purchase_date_to},
      success:function(res){
        $.each(res, function(key, value){
          var total_sold = 0;
          if(value['sales_details'] != null){
            total_sold = value['sales_details']['total_sold'];
          }
          var tr = '<tr>'+
          '<td>'+value['sheet_wastage']['sheet_wastage_code']+'</td>'+
          '<td>'+value['sheet_wastage']['sheet_wastage_id']+'</td>'+
          '<td>'+value['total_purchased']+'</td>'+
          '<td>'+total_sold+'</td>'+
          '<td>'+value['kutcha_wastage_stock']['available_quantity']+'</td>'+
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