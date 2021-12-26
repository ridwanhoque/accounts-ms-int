@extends('admin.master')
@section('content')

<main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-th-list"></i> Haddi Powder Information</h1>
          <p>Haddi Powder information </p>
        </div>
        <ul class="app-breadcrumb breadcrumb side">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item">Haddi Powder Information</li>
          <li class="breadcrumb-item active"><a href="#">Haddi Powder Information Table</a></li>
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
            <h3 class="tile-title">Haddi Powder List </h3>
            
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
                    <th>Haddi (kg)</th>
                    <th>Powder (kg)</th>
                  </tr>
                </thead>
                <tbody>
                    @isset($haddi_powder_stocks)
                        @foreach ($haddi_powder_stocks as $haddi_powder_stock)
                        <tr>
                          <td>{{ $haddi_powder_stock->sub_raw_material->raw_material->name.' - '.$haddi_powder_stock->sub_raw_material->name }}</td>
                          <td>{{ $haddi_powder_stock['haddi'] ?: '0.00' }} {{ ' '.config('app.kg') }}</td>
                          <td>{{ $haddi_powder_stock['powder'] ?: '0.00' }} {{ ' '.config('app.kg') }}</td>
                        </tr>
                        @endforeach
                    @endisset
                      
                </tbody>
              </table>
              {{ $haddi_powder_stocks->links() }}
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
    </script>
  @include('admin.includes.date_field')

  @include('admin.includes.delete_confirm')

    @endsection