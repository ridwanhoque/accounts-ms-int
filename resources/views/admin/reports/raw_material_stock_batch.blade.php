@extends('admin.master')
@section('content')

<main class="app-content">
  <div class="app-title">
    <div>
      <h1><i class="fa fa-th-list"></i> Raw Material Stock Information</h1>
      <p>Raw Material Stock information </p>
    </div>
    <ul class="app-breadcrumb breadcrumb side">
      <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
      <li class="breadcrumb-item">Raw Material Stock Information</li>
      <li class="breadcrumb-item active"><a href="#">Raw Material Stock Information Table</a></li>
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
        <h3 class="tile-title">Raw Material Stock List </h3>

        <div class="tile-body">
          @if(Session::get('error_message'))
          <div class="alert alert-danger">
            {{ Session::get('error_message') }}
          </div>
          @endif
          <form>
            {{--
              <table class="no-spacing">
              <tr>
                <th style="padding: 10px;">
                  Raw Material
                </th>
                <td style="padding: 10px; width:200px;">
                  <select name="sub_raw_material_id" class="form-control select2">
                    <option value="">select</option>
                    @foreach ($sub_raw_materials as $sub_raw_material)
                    <option value="{{ $sub_raw_material->id }}"
                      {{ request()->sub_raw_material_id == $sub_raw_material->id ? 'selected':'' }}>
                      {{ $sub_raw_material->raw_material ? $sub_raw_material->raw_material->name.' - '.$sub_raw_material->name:$sub_raw_material->name }}
                    </option>
                    @endforeach
                  </select>
                </td>
                <th style="padding: 10px;">
                  From
                </th>
                <td style="padding: 10px;">
                  <input type="text" class="form-control dateField" name="start_date"
                    value="{{ request()->start_date }}">
                  <div class="text-danger">{{ $errors->has('start_date') ? $errors->first('start_date'):'' }}</div>
                </td>
                <th style="padding: 10px;">
                  To
                </th>
                <td style="padding: 10px;">
                  <input type="text" class="form-control dateField" name="end_date" value="{{ request()->end_date }}">
                  <div class="text-danger">{{ $errors->has('end_date') ? $errors->first('end_date'):'' }}</div>
                </td>
                <td style="padding: 10px;">
                  <button type="submit" class="btn btn-sm btn-primary">
                    <i class="fa fa-filter"></i>
                  </button>
                </td>
              </tr>
            </table>
              --}}          
          </form>
          <div class="mt-4">

          </div>
          <table class="table table-bordered text-center" id="stock_table">
            <thead>
              <tr>
                <th>Raw Material Name</th>
                <th>Batch</th>
                <th>Purchased (kg)</th>
                <th>Used (kg)</th>
                <th>Available (kg)</th>
                <!-- <th>Price Value</th> -->
              </tr>
            </thead>
            <tbody>
              @isset($sub_raw_material_stocks)
                @foreach ($sub_raw_material_stocks as $rm_stock)
                  <tr>
                    <td>{{ $rm_stock->sub_raw_material->raw_material->name.' - '.$rm_stock->sub_raw_material->name }}</td>
                    <td>{{ $rm_stock->batch->name }}</td>
                    <td>{{ $rm_stock['purchased_quantity'] ?: '0.00' }} {{ config('app.kg') }}</td>
                    <td>{{ $rm_stock['used_quantity'] ?: '0.00' }} {{ config('app.kg') }}</td>
                    <td>{{ $rm_stock['available_quantity'] ?: '0.00' }} {{ config('app.kg') }}</td>
                    <!-- <td>{{ $rm_stock['price_value'] }}</td> -->
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