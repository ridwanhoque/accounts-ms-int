@extends('admin.master')
@section('content')

<main class="app-content">
  <form class="form-horizontal" method="POST" action="{{ route('assets.store') }}">
    @csrf

    <div class="app-title">
      <div>
        <h1><i class="fa fa-edit"></i> Asset</h1>
        <p>Create Asset Form</p>
      </div>
      <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item">Assets</li>
        <li class="breadcrumb-item"><a href="#">Add Asset</a></li>
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

          <a href="{{route('assets.index')}}" class="btn btn-primary pull-right" style="float: right;"><i class="fa fa-eye"></i>View Asset</a>
          <h3 class="tile-title">Add New Asset</h3>

          <div class="tile-body">


            <div class="form-group row add_asterisk">
              <label class="control-label col-md-3">Date</label>
              <div class="col-md-8">
                <input name="asset_date" value="{{ old('asset_date') == '' ? date('Y-m-d'):old('asset_date') }}" class="form-control" type="text" placeholder="Date" readonly="readonly">
              </div>
            </div>

            <div class="form-group row add_asterisk">
              <label class="control-label col-md-3">Vendor/Supplier</label>
              <div class="col-md-8">
                <select name="party_id" class="form-control select2">
                  @foreach ($parties as $party)
                  <option value="{{ $party->id }}">{{ $party->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="form-group row">
              <label class="control-label col-md-3">Reference</label>
              <div class="col-md-8">
                <input name="asset_reference" value="" class="form-control" type="text" placeholder="Reference">
              </div>
            </div>



            <div class="mt-2"></div>

            <input type="hidden" name="status_id" value="1">

            <!-- add product -->


            <div class="container">
              <div class="row clearfix">
                <div class="col-md-12">
                  <table class="table no-spacing" id="asset_table">
                    <thead>
                      <tr>
                        <th width="25%" class="text-center"> Asset Head</th>
                        <th class="text-center"> Amount </th>
                        <th class="text-center" width="8%"> Years </th>
                        <th class="text-center">Account Information</th>
                        <th class="text-center"> Payment Method</th>
                        <th class="text-center">Note</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr id="addr_asset_table0">
                        <td>
                          <select name="chart_of_account_id[]" class="form-control small_input_box">
                            <option value="0">select</option>
                            @foreach($chart_of_accounts as $chart_of_account)
                            <option value="{{ $chart_of_account->id }}">{{ $chart_of_account->head_name }}</option>
                            @endforeach
                          </select>
                        </td>
                        <td><input type="number" class="form-control small_input_box amount" name="amount[]"></td>
                        <td><input type="number" name="years[]" class="form-control small_input_box"></td>
                        <td>
                          <select name="account_information_id[]" class="form-control small_input_box" onchange="loadPaymentMethods(this)">
                            <option value="0">select</option>
                            @foreach($accounts as $account)
                            <option value="{{ $account->id }}">{{ $account->account_name }}</option>
                            @endforeach
                          </select>
                        </td>
                        <td>
                          <select name="payment_method_id[]" class="form-control small_input_box payment_methods">
                            <option value="0">select</option>
                          </select>
                        </td>
                        <td>
                          <input type="text" class="form-control small_input_box" name="cheque_number[]">
                        </td>
                      </tr>
                      <tr id="addr_asset_table1"></tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="row clearfix">
                <div class="col-md-12">
                  <span class="fa fa-trash btn btn-sm btn-danger pull-right pointer small_input_button" id="delete_row_asset_table"></span>
                  <span class="fa fa-plus btn btn-sm btn-success pull-right pointer small_input_button" id="add_row_asset_table"></span>
                </div>
              </div>
              <div class="row pull-right clearfix" style="margin-top:20px">
                <div class="col-md-12">
                  <table class="table" id="tab_logic_total">
                    <tbody>
                      <tr>
                        <th class="text-center">Total</th>
                        <td class="text-center"><input type="number" name='total_amount' placeholder='0.00' class="form-control" id="total_amount" readonly /></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="clearfix"></div>
            </div>

            <div class="tile-footer">
              <div class="row">
                <div class="col-md-12">
                  <button class="btn btn-primary pull-right" type="submit" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Add Asset</button>
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
  function loadPaymentMethods(element) {
    var id = element.value;
    var row = $(element).closest('tr');

    $.ajax({
      url: '{{ url("api/payment_methods/get_payment_method") }}',
      type: 'GET',
      data: 'id=' + id,
      success: function(res) {
        row.find('.payment_methods').html(res['payment_method_dropdown']);
      }
    });
  }


  $('#asset_table').on('keyup', function() {
    total_amount();
  });

  function total_amount() {
    var total_amount = 0;

    $('.amount').each(function() {
      var amount = $(this).val() > 0 ? $(this).val() : 0;
      total_amount += parseFloat(amount);
    });

    $('#total_amount').val(total_amount.toFixed(2));
  }

  table_repeater('asset_table');

  function table_repeater(tableId) {

    var i = 1;
    $("#add_row_" + tableId).click(function() {
      b = i - 1;
      $('#addr_' + tableId + i).html($('#addr_' + tableId + b).html());
      $('#' + tableId).append('<tr id="addr_' + tableId + (i + 1) + '"></tr>');
      i++;
    });
    $("#delete_row_" + tableId).click(function() {

      if (i > 1) {
        $("#addr_" + tableId + (i - 1)).html('');
        i--;
      }

    });

  }
</script>

@include('admin.includes.date_field')
@endsection