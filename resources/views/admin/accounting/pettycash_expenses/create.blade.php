@extends('admin.master')
@section('content')

<main class="app-content">
  <form class="form-horizontal" method="POST" action="{{ route('pettycash_expenses.store') }}">
    @csrf

    <div class="app-title">
      <div>
        <h1><i class="fa fa-edit"></i> Pettycash Expense Information</h1>
        <p>Create Pettycash Expense Form</p>
      </div>
      <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item">Pettycash Expenses</li>
        <li class="breadcrumb-item"><a href="#">Add Pettycash Expense</a></li>
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

          <a href="{{route('pettycash_expenses.index')}}" class="btn btn-primary pull-right" style="float: right;"><i
              class="fa fa-eye"></i>View Pettycash Expense</a>
          <h3 class="tile-title">Add New Pettycash Expense</h3>

          <div class="tile-body">

            <div class="form-group row">
              <label class="control-label col-md-3">Date
                <input type="text" class="form-control" name="pettycash_expense_date" value="{{ date('Y-m-d') }}"
                  readonly="readonly"></label>
            </div>

            <div class="container">
              <div class="row clearfix">
                <div class="col-md-12">
                  <table class="table table-bordered" id="tab_logic">
                    <thead>
                      <tr>
                        <th class="text-center"> # </th>
                        <th width="30%" class="text-center"> Pettycash Head</th>
                        <th width="50%" class="text-center"> Purpose </th>
                        <th class="text-center"> Amount </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr id='addr0'>
                        <td>1</td>
                        <td>
                          <select name="pettycash_chart_ids[]" class="form-control">
                            @foreach ($pettycash_charts as $chart)
                            <option value="{{ $chart->id }}">{{ $chart->name }}</option>
                            @endforeach
                          </select>
                          <div class="text-danger">
                            {{ $errors->has('pettycash_chart_ids.*') ? $errors->first('pettycash_chart_ids.*'):'' }}
                          </div>
                        </td>
                        <td>
                          <input type="text" name="purpose[]" class="form-control" placeholder="Enter Purpose">
                          <div class="text-danger">{{ $errors->has('purpose.*') ? $errors->first('purpose.*'):'' }}
                          </div>
                        </td>
                        <td><input type="number" name='amount[]' placeholder='Enter Amount' class="form-control amount"
                            min="0" />
                          <div class="text-danger">{{ $errors->has('amount.*') ? $errors->first('amount.*'):'' }}</div>
                        </td>
                      </tr>
                      <tr id='addr1'></tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="row clearfix">
                <div class="col-md-12">
                  <span class="fa fa-trash pull-right pointer btn btn-sm btn-danger" id="delete_row"></span>
                  <span class="fa fa-plus pull-right pointer btn btn-sm btn-success" id="add_row"></span>
                </div>
              </div>
              <div class="row pull-right clearfix" style="margin-top:20px">
                <div class="col-md-12">
                  <table class="table" id="tab_logic_total">
                    <tbody>
                      <tr>
                        <th class="text-center">Available Balance</th>
                        <td class="text-center"><input type="number" name='sub_total' placeholder='0.00'
                            class="form-control" id="sub_total" readonly="readonly" /></td>
                      </tr>

                      <tr>
                        <th class="text-center">Grand Total</th>
                        <td class="text-center"><input type="number" name='total_amount' id="total_amount"
                            placeholder='0.00' min="0" class="form-control" readonly="readonly" /></td>
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
                  <button class="btn btn-primary pull-right" type="submit"><i
                      class="fa fa-fw fa-lg fa-check-circle"></i>Add Pettycash Expense</button>
                </div>
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

<script>
  $('.select2').select2();
</script>

<script>
  $(document).ready(function(){

  var i=1;
    $("#add_row").click(function(){
        b=i-1;
      	$('#addr'+i).html($('#addr'+b).html()).find('td:first-child').html(i+1);
      	$('#tab_logic').append('<tr id="addr'+(i+1)+'"></tr>');
      	i++; 
  	});
    $("#delete_row").click(function(){
    	if(i>1){
		$("#addr"+(i-1)).html('');
		i--;
		}
		calc();
	});

  $('#tab_logic tbody').on('keyup change', function(){
    calc();
  });

  function calc(){
    var total = 0;

    $('.amount').each(function(){
      total += parseInt($(this).val());
    });

    $('#total_amount').val(total.toFixed(2));
  }

 
});
</script>

@endsection