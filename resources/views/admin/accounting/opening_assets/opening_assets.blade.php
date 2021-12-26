@extends('admin.master')
@section('content')
@section('title', 'Opening Asset')
<main class="app-content">
  <form class="form-horizontal" method="POST" action="{{ url('opening_asset_store') }}">
    @csrf

    <div class="app-title">
      <div>
        <h1><i class="fa fa-edit"></i> @yield('title') Information</h1>
        <p>Create @yield('title') Form</p>
      </div>
      <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item">@yield('title')s</li>
        <li class="breadcrumb-item"><a href="#">Add @yield('title')</a></li>
      </ul>
    </div>
    <div class="row">
      <div class="col-md-12">
        @if(Session::get('error_message'))
        <div class="alert alert-danger">
          {{ Seession::get('error_message') }}
        </div>
        @endif
        @if($errors->any())
        <div class="alert-danger">
          <ul class="list-unstyled">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
        @endif

        <div class="tile">

          <h3 class="tile-title">@yield('title')</h3>

          <div class="tile-body">

            @if(Session::get('message'))
            <div class="alert alert-success">
                {{ Session::get('message') }}
            </div>
            @endif

            <div class="row">
              <div class="col-md-12">
                  @foreach ($chart_of_accounts as $key => $chart_of_account)
                  @php
                      $opening_asset = $chart_of_account->opening_asset;
                      if($key%4 == 0) { echo '<div class="clearfix"></div>'; }
                  @endphp
                  
                  <div class="form-group float-left">
                    <label class="control-label"><strong>{{ $chart_of_account->head_name }}</strong></label>
                    <div style="padding-right: 50px;">
                      <input type="hidden" name="chart_of_account_ids[]" value="{{ $chart_of_account->id }}">
                      Amount :
                      <input name="opening_amount[]" value="{{ $opening_asset ? $opening_asset->opening_amount:'0.00' }}" class="form-control small_input_box" type="text"
                        placeholder="Amount">
                      <div class="text-danger">
                        {{ $errors->has('opening_amount.*') ? $errors->first('opening_amount.*'):'' }}</div>
                        Years: 
                        <input name="years[]" value="{{ $opening_asset ? $opening_asset->years:'0' }}" class="form-control small_input_box" type="text"
                        placeholder="Years">
                      <div class="text-danger">
                        {{ $errors->has('years.*') ? $errors->first('years.*'):'' }}</div>
                    </div>
                  </div>
                  @endforeach      
              </div>
            </div>
 
          </div>


          @if($chart_of_accounts->count() > 0)
          <div class="tile-footer">
            <div class="row">
              <div class="col-md-12">
                <button class="btn btn-primary pull-right" type="submi`t" type="submit"><i
                    class="fa fa-fw fa-lg fa-check-circle"></i>Add @yield('title')</button>
              </div>
            </div>
          </div>
          @endif


        </div>


      </div>

    </div>
    </div>
    </div>

  </form>
</main>

<script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/plugins/select2.min.js')}}"></script>


@endsection