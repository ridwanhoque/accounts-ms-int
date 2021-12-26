@extends('admin.master')
@section('content')

<main class="app-content">
  <div class="app-title">
    <div>
      <h1><i class="fa fa-th-list"></i> Opening RM Stock Information</h1>
      <p>Opening RM Stock information </p>
    </div>
    <ul class="app-breadcrumb breadcrumb side">
      <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
      <li class="breadcrumb-item">Opening RM Stock Information</li>
      <li class="breadcrumb-item active"><a href="#">Opening RM Stock Information Table</a></li>
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
        <h3 class="tile-title">Opening RM Stock List </h3>

        <div class="tile-body">
          @if(Session::get('error_message'))
          <div class="alert alert-danger">
            {{ Session::get('error_message') }}
          </div>
          @endif
          
          <div class="mt-4">

          </div>
          <table class="table table-bordered text-center" id="stock_table">
            <thead>
              <tr>
                <th>Raw Material Name</th>
                <th>Opening Stock (kg)</th>
                <th>Used (kg)</th>
                <th>Available (kg)</th>
              </tr>
            </thead>
            <tbody>
              @isset($sub_raw_material_stocks)
                @foreach ($sub_raw_material_stocks as $rm_stock)
                  <tr>
                    <td>{{ $rm_stock->sub_raw_material->raw_material->name.' - '.$rm_stock->sub_raw_material->name }}</td>
                    <td>{{ $rm_stock['opening_quantity'] ?: '0.00' }} {{ config('app.kg') }}</td>
                    <td>{{ $rm_stock['used_opening_quantity'] ?: '0.00' }} {{ config('app.kg') }}</td>
                    <td>{{ $rm_stock['available_opening_quantity'] ?: '0.00' }} {{ config('app.kg') }}</td>
                  </tr>
                @endforeach
              @endisset

            </tbody>
          </table>
          {{ $sub_raw_material_stocks->links() }}
        </div>
      </div>
    </div>
  </div>
</main>


<script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>
<!-- Data table plugin-->
<script type="text/javascript" src="{{ asset('assets/admin/js/plugins/jquery.dataTables.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('assets/admin/js/plugins/dataTables.bootstrap.min.js')}}"></script>

<script src="{{ asset('assets/admin/js/plugins/select2.min.js')}}"></script>


<script type="text/javascript">
  $('.select2').select2();
</script>

<script>
  $("#submitBtn1").on('click', function(){

  $('#stock_table tbody').empty();

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