@extends('admin.master')
@section('content')

<main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-th-list"></i> Forming Wastage Stock Information</h1>
          <p>Forming Wastage Stock information </p>
        </div>
        <ul class="app-breadcrumb breadcrumb side">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item">Forming Wastage Stock Information</li>
          <li class="breadcrumb-item active"><a href="#">Forming Wastage Stock Information Table</a></li>
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
            <h3 class="tile-title">Forming Wastage Stock List </h3>
            
            <div class="tile-body">
                  <div class="mt-4"></div>
              <table class="table table-bordered" id="stock_table">
                <thead>
                  <tr>
                    <th>Raw Material Name</th>
                    <th>Forming Wastage Total (kg)</th>
                    <th>Forming Wastage Used (kg)</th>
                    <th>Forming Wastage Available (kg)</th>
                  </tr>
                </thead>
                <tbody>
                      @isset($forming_wastage_stocks)
                        @foreach ($forming_wastage_stocks as $wastage_stock)
                        <tr>
                          <td>
                          @foreach ($wastage_stock->sheet_production_details->sheets as $key => $sheet)
                              {{ $key>0 ? ', ':'' }}
                              {{ $sheet->sub_raw_material->raw_material->name.' - '.$sheet->sub_raw_material->name }}  
                          @endforeach
                          </td>
                          <td>{{ $wastage_stock['total_quantity'] ?: 0 }}</td>
                          <td>{{ $wastage_stock['used_quantity'] ?: 0 }}</td>
                          <td>{{ $wastage_stock['available_quantity'] ?: 0 }}</td>
                        </tr>
                        @endforeach
                    @endisset
                      
                </tbody>
              </table>
              {{ $forming_wastage_stocks->links() }}
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
  var forming_wastage_id = $("#forming_wastage_id").val();
  var purchase_date_from = $("#purchase_date_from").val();
  var purchase_date_to = $("#purchase_date_to").val();
  var url_data = 'forming_wastage_id='+forming_wastage_id+'&purchase_date_from='+purchase_date_from+'&purchase_date_to='+purchase_date_to;
      $.ajax({
      url: "{{ url('reports/forming_wastage_stocks/filter') }}",
      type: "GET",
      data: {forming_wastage_id:forming_wastage_id, purchase_date_from: purchase_date_from, purchase_date_to: purchase_date_to},
      success:function(res){
        $.each(res, function(key, value){
          var total_sold = 0;
          if(value['sales_details'] != null){
            total_sold = value['sales_details']['total_sold'];
          }
          var tr = '<tr>'+
          '<td>'+value['forming_wastage']['forming_wastage_code']+'</td>'+
          '<td>'+value['forming_wastage']['forming_wastage_id']+'</td>'+
          '<td>'+value['total_purchased']+'</td>'+
          '<td>'+total_sold+'</td>'+
          '<td>'+value['forming_wastage_stock']['available_quantity']+'</td>'+
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