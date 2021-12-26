@extends('admin.master')
@section('content')
@section('page_title', 'Chart Of Account')
<main class="app-content">
  <form class="form-horizontal" method="POST" action="{{ route('chart_of_accounts.store') }}">
    @csrf

    <div class="app-title">
      <div>
        <h1><i class="fa fa-edit"></i> @yield('page_title')</h1>
        <p>Create @yield('page_title') Form</p>
      </div>
      <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item">@yield('page_title')</li>
        <li class="breadcrumb-item"><a href="#">Add @yield('page_title')</a></li>
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

          <a href="{{route('chart_of_accounts.index')}}" class="btn btn-primary pull-right" style="float: right;"><i class="fa fa-eye"></i>View @yield('page_title')</a>
          <h3 class="tile-title">Add New @yield('page_title')</h3>

          <div class="tile-body">

            <div class="form-group row add_asterisk">
                <label for="parent_id" class="control-label col-md-3">Parent </label>
                <div class="col-md-9">
                  <select name="parent_id" id="parent_id" class="form-control select2 @error('parent_id') is-invalid @enderror" onchange="load_charts(this.value)">
                    <option value="">Self</option>
                    @foreach ($chart_of_accounts as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                  </select>

                  @error('parent_id')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror

                </div>
              </div>

            <div class="form-group row add_asterisk">
              <label for="type" class="control-label col-md-3">Type </label>
              <div class="col-md-9">
                <select name="chart_type_id" id="chart_type_id" class="form-control @error('type') is-invalid @enderror">
                  @foreach($chart_of_account_types as $id => $name)
                  <option value="{{ $id }}">{{ $name }}</option>
                  @endforeach
                </select>

                @error('type')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror

              </div>
            </div>





            <div class="form-group row add_asterisk">
              <label for="type" class="control-label col-md-3">Category </label>
              <div class="col-md-9">
                <select name="owner_type_id" id="owner_type_id" class="select2 form-control @error('type') is-invalid @enderror">
                  @foreach($owner_types as $id => $name)
                  <option value="{{ $id }}">{{ $name }}</option>
                  @endforeach
                </select>

                @error('type')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror

              </div>
            </div>


            <div class="form-group row add_asterisk">
              <label for="head_name" class="control-label col-md-3">Head Name </label>
              <div class="col-md-9">
                <input id="head_name" name="head_name" value="{{ old('head_name') }}" type="text" class="form-control @error('head_name') is-invalid @enderror" placeholder="Head Name">

                @error('head_name')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror

              </div>
            </div>
            <div class="form-group row add_asterisk">
              <label for="head_name" class="control-label col-md-3">Opening Balance </label>
              <div class="col-md-9">
                <input id="opening_balance" name="opening_balance" value="{{ old('opening_balance') }}" type="text" class="form-control @error('opening_balance') is-invalid @enderror" placeholder="Opening Balance">

                @error('opening_balance')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror

              </div>
            </div>

            <input type="hidden" name="account_code" id="account_code" value="">
            <input type="hidden" name="tire" id="tire" value="">
            <input type="hidden" name="is_posting" value="1">
            <input type="hidden" name="status" value="1">

            <div class="tile-footer">
              <div class="row">
                <div class="col-md-12">
                  <button class="btn btn-primary pull-right" type="submit" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Add @yield('page_title')</button>
                </div>
              </div>
            </div>


          </div>

        </div>
      </div>
    </div>







  </form>
</main>

<script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/plugins/select2.min.js')}}"></script>

<script src="{{ asset('assets/admin/js/style.js') }}"></script>

<script type="text/javascript">
  $('.select2').select2();
</script>




<script>
  selected_materials = [];

  $('.raw_material').each(function(key, value) {
    //   if($.inArray(value, selected_materials) == -1){
    //     selected_materials.push(value);
    //   }
    //  alert(parseInt(selected_materials));
  });

  // console.log(selected_materials);

  $(document).ready(function() {

    load_charts('');

    // $('.raw_material').change(function(){
    //   alert();
    // });


    var i = 1;
    $("#add_row").click(function() {

      // $('.select2').select2();
      b = i - 1;
      $('#addr' + i).html($('#addr' + b).html());
      $('#tab_logic').append('<tr id="addr' + (i + 1) + '"></tr>');
      i++;
    });
    $("#delete_row").click(function() {
      if (i > 1) {
        $("#addr" + (i - 1)).html('');
        i--;
      }
      calc();
    });

    $('#tab_logic tbody').on('keyup change', function() {
      calc();
    });
    $('#tax').on('keyup change', function() {
      calc_total();
    });

    $('#invoice_discount').on('keyup change', function() {
      calc_total();
    });


  });

  function calc() {
    $('#tab_logic tbody tr').each(function(i, element) {
      var html = $(this).html();
      if (html != '') {
        var qty = $(this).find('.qty').val();
        var price = $(this).find('.price').val();

        $(this).find('.total').val(qty * price);

        calc_total();
      }
    });
  }

  function calc_total() {
    total = 0;
    $('.total').each(function() {
      total += parseFloat($(this).val());
    });
    $('#sub_total').val(total.toFixed(3));
    tax_sum = total / 100 * $('#tax').val();
    $('#invoice_tax').val(tax_sum.toFixed(3));
    var discount = $('#invoice_discount').val();
    $('#total_amount').val(((tax_sum + total) - discount).toFixed(3));
    $('#total_paid').val(((tax_sum + total) - discount).toFixed(3));
  }
</script>

{{-- replace tire on change   --}}
<script type="text/javascript">
  function load_charts(parent_id){
        var parent_id = parent_id;
        var chart_type_id = $('#chart_type_id').val();
        
        $.ajax({
            url: '{{ url("api/chart_of_accounts/get_charts") }}',
            type: 'GET',
            data: 'parent_id='+parent_id+'&chart_type_id='+chart_type_id,
            success: function(res){
                $('#tire').val(res['tire']);
                $('#account_code').val(res['account_code']);
                $('#chart_type_id').val(res['chart_type_id']);
            }
        });
    };
</script>

@include('admin.includes.date_field')
@endsection
