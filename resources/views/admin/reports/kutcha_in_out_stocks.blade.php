@extends('admin.master')
@section('content')
<main class="app-content">
  <div class="app-title">
    <div>
      <h1><i class="fa fa-th-list"></i> Kucha In Out Information</h1>
      <p>Kucha In Out information </p>
    </div>
    <ul class="app-breadcrumb breadcrumb side">
      <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
      <li class="breadcrumb-item">Kucha In Out Information</li>
      <li class="breadcrumb-item active"><a href="#">Kucha In Out Information Table</a></li>
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
        <h3 class="tile-title">Kucha In Out List </h3>

        <div class="tile-body">
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-body">
                  <form method="" action="">
                    <table class="no-spacing">
                      <tr>
                        <input type="hidden" name="fm_kutcha_id">
                        <th>From : </th>
                        <td>
                          <input type="text" name="from_date" class="form-control dateField" value="{{ request()->from_date }}">
                        </td>
                        <th>To :</th>
                        <td>
                          <input type="text" name="to_date" class="form-control dateField" value="{{ request()->to_date }}">
                        </td>
                        <td style="padding: 10px;">
                          <button type="submit" id="submitBtn" class="btn btn-primary" style="padding: 5px ;"><i class="fa fa-filter"></i></button>
                        </td>
                      </tr>
                    </table>
                  </form>
                </div>
              </div>
            </div>
          </div>

          <div class="mt-4"></div>

          <table class="table table-bordered" id="stock_table">
            <thead>
              <tr>
                <th>Kutcha Name</th>
                <th>Opening Stock</th>
                <th>Kutcha Out</th>
                <th>Kutcha Used</th>
                <th>Balance</th>
              </tr>
            </thead>
            <tbody>

              @isset($kutcha_stocks)
              @foreach ($kutcha_stocks as $kutcha_stock)
                @php
                  $opening_kg = $kutcha_stock->fm_kutcha->fm_kutcha_opening['opening_kg'] ?: 0;
                @endphp
              <tr>
                <td>{{ $kutcha_stock->fm_kutcha ? $kutcha_stock->fm_kutcha->raw_material->name.'-'.$kutcha_stock->fm_kutcha->name:'' }}</td>
                <td>{{ $openig_with_day_opening = $kutcha_stock->fm_kutcha->fm_kutcha_day_opening['opening_qty']+$opening_kg }} {{ config('app.kg') }}</td>
                <td>{{ $kutcha_stock_in = $kutcha_stock->fm_kutcha_out_sum }} {{ config('app.kg') }} </td>
                <td>{{ $kutcha_stock_out = $kutcha_stock->fm_kutcha->fm_kutcha_sheet_sum['sum_qty_kg'] }} {{ config('app.kg') }}</td>
                <td>{{ $balance = $openig_with_day_opening-$kutcha_stock_out.' '.config('app.kg') }}</td>
              </tr>
              @endforeach
              @endisset

            </tbody>
          </table>
          {{ $kutcha_stocks->links() }}
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
</script>
@include('admin.includes.date_field')

@include('admin.includes.delete_confirm')

@endsection