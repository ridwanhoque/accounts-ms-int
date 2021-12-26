@extends('admin.master')
@section('title','Payment Voucher')
@section('content')
<style>
    .select2-selection__rendered {
        line-height: 14px !important;
    }

    .select2-container .select2-selection--single {
        height: 25px !important;
    }

    .select2-selection__arrow {
        height: 25px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 20px;
    }
</style>

<main class="app-content">


    <form class="form-horizontal" method="POST" action="{{ route('payment_vouchers.store') }}">
        @csrf
        <input type="hidden" name="account_balance" id="account_balance" value="0">
        <input type="hidden" name="voucher_type" value="{{ request()->type }}">
        <input type="hidden" name="payment_type" value="{{ request()->payment_type ?: 'voucher' }}">


        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i> @yield('title')</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item"><a href="#">@yield('title')</a></li>
            </ul>
        </div>

        @if(Session::get('message'))
        <div class="alert alert-success alert-dismissible">
            <button class="close" type="button" data-dismiss="alert">x</button>
            {{ Session::get('message') }}
        </div>
        @endif

        @if ($errors->any())
        <div class="alert alert-dismissible alert-danger">
            <button class="close" type="button" data-dismiss="alert">×</button>
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="row">
            <div class="col-md-12">

                <div class="tile">
                    <a href="{{ route('payment_vouchers.index') }} }}" class="btn btn-primary" style="float: right;"><i class="fa fa-list"></i> Voucher List</a>
                    <h3 class="tile-title"><i class="fa fa-plus"></i> &nbsp; Add New @yield('title')</h3>
                    <hr>
                    <div class="tile-body">

                        <div class="row">
                            <div class="col-sm-10 offset-md-1">
                                <div class="row">


                                    <div class="col-md-3">

                                        <div class="form-group row">
                                            <label for="payment_date" class="control-label col-md-4">Date</label>
                                            <div class="col-md-8">
                                                <input id="payment_date" name="payment_date" readonly value="{{ date('Y-m-d') }}" type="text" class="form-control small_input_box @error('payment_date') is-invalid @enderror" placeholder="Payment Date">

                                                @error('payment_date')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror

                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                            <label for="ref_id" class="control-label col-md-4">Reference(#ID)</label>
                                            <div class="col-md-8">
                                                <input id="ref_id" name="ref_id" value="{{ old('ref_id') }}" type="text" class="form-control small_input_box @error('ref_id') is-invalid @enderror" placeholder="Reference(#ID)">

                                                @error('ref_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror

                                            </div>
                                        </div>


                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group row">
                                            <label for="note" class="control-label col-md-4">Note</label>
                                            <div class="col-md-8">
                                                <input id="note" name="note" value="{{ old('note') }}" type="text" class="form-control small_input_box @error('note') is-invalid @enderror" placeholder="Note">

                                                @error('note')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror

                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- <div class="row">
                            <div class="col-sm-10 offset-md-1">
                               <div class="row">


                                    <div class="col-md-3">

                                        <label for="chart_of_account_id" class="control-label col-md-4">Account</label>
                                        <div class="col-md-8">
                                            <div class="form-group row">
                                                <select name="chart_of_account_id" class="form-control small_input_box" onchange="show_credit_balance(this)">
                                                    <option value="">select</option>
                                                    @foreach($data['bank_cash_charts'] as $id => $name)
                                                    <option value="{{ $id }}" {{ $id == old('chart_of_account_id') ? 'selected':'' }}>
                                                        {{ $name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                            <label for="balance" class="control-label col-md-4">Balance</label>
                                            <div class="col-md-8">
                                                <input id="credit_balance" name="credit_balance" value="{{ old('credit_balance') }}" type="number" class="small_input_box form-control @error('credit_balance') is-invalid @enderror" placeholder="Balance" readonly="readonly">
                                            </div>
                                        </div>


                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group row">
                                            <label for="note" class="control-label col-md-4">Credit</label>
                                            <div class="col-md-8">
                                                <input id="credit_amount" name="credit_amount" readonly="readonly" value="{{ old('credit_amount') ?? 0 }}" type="number" class="form-control small_input_box @error('credit_amount') is-invalid @enderror" placeholder="Amount">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div> -->
                        <br>

                        <div class="row">

                            <div class="col-md-10 offset-md-1">
                            <h4>Credit</h4>
                                <table class="no-spacing" id="credit_table">
                                    <tr>
                                        <th>Chart of Account</th>
                                        <th>Balance</th>
                                        <th>Description</th>
                                        <th>Debit</th>
                                        <th></th>
                                    </tr>

                                    @if(old('chart_of_account_ids'))

                                    @foreach(old('chart_of_account_ids') as $key => $chart_of_account_id)
                                    <tr id="addr_credit_table0">
                                        <td>
                                            <select name="chart_of_account_ids[]" class="form-control chart_id_credit0" onchange="show_debit_balance(this)">
                                                <option value="">- Select Chart Of Account -</option>
                                                @foreach($data['chart_of_accounts'] as $id => $name)
                                                <option value="{{ $id }}" {{ old('chart_of_account_ids')[$key] == $id ? 'selected':'' }}>
                                                    {{ $name }}</option>
                                                @endforeach

                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control small_input_box debit_balance" readonly="readonly" name="debit_balance[]" value="{{ old('debit_balance')[$key] }}">
                                        </td>
                                        <td width="30%">
                                            <input name="description[]" class="form-control small_input_box" type="text" placeholder="Description" value="{{ old('description')[$key] ?: '' }}">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control small_input_box amount" name="amount[]" placeholder="0.00" onkeyup="show_total(this)" value="{{ old('amount')[$key] }}">
                                        </td>
                                        <td>

                                        </td>
                                    </tr>
                                    @endforeach

                                    @else
                                    <tr id="addr_credit_table0">
                                        <td width="30%">
                                            <select name="chart_of_account_ids[]" class="form-control chart_id_credit0" onchange="show_debit_balance(this)">
                                                <option value="">- Select Chart Of Account -</option>
                                                @foreach($data['chart_of_accounts'] as $id => $name)
                                                <option value="{{ $id }}">
                                                    {{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control small_input_box debit_balance" readonly="readonly" name="debit_balance[]">
                                        </td>
                                        <td width="30%">
                                            <input name="description[]" class="form-control small_input_box" type="text" placeholder="Description">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control small_input_box amount" name="amount[]" placeholder="0.00" onkeyup="show_total(this)">
                                        </td>
                                        <td>

                                        </td>
                                    </tr>
                                    @endif

                                    <tr id="addr_credit_table1"></tr>

                                </table>
                            </div>

                        </div>


                        <div class="mt-2"></div>

                        <div class="row clearfix">
                            <div class="col-md-12">
                                <div class="pull-right">
                                    <th>
                                        <span class="fa fa-plus btn btn-sm btn-success outer small_input_button" data-repeater-create id="add_row_credit_table"></span>
                                        <span class="fa fa-trash btn btn-sm btn-danger outer small_input_button btnRemove" id="delete_row_credit_table"></span>
                                    </th>
                                </div>
                            </div>
                        </div>

                        <br>
                        <br>



                    </div>

                    <hr>


                    <div class="row">

                            <div class="col-md-10 offset-md-1">
                            <h4>Debit</h4>
                                <table class="no-spacing" id="debit_table">
                                    <tr>
                                        <th>Chart of Account</th>
                                        <th>Balance</th>
                                        <th>Description</th>
                                        <th>Debit</th>
                                        <th></th>
                                    </tr>
                                    <tbody>
                                                @if(old('chart_of_account_ids'))

                                                @foreach(old('chart_of_account_ids') as $key => $chart_of_account_id)
                                                <tr id="addr_debit_table0">
                                                    <td>
                                                        <select name="chart_of_account_ids[]" class="form-control" onchange="show_debit_balance(this)">
                                                            <option value="">- Select Chart Of Account -</option>
                                                            @foreach($data['chart_of_accounts'] as $id => $name)
                                                            <option value="{{ $id }}" {{ old('chart_of_account_ids')[$key] == $id ? 'selected':'' }}>
                                                                {{ $name }}</option>
                                                            @endforeach

                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control small_input_box debit_balance" readonly="readonly" name="debit_balance[]" value="{{ old('debit_balance')[$key] }}">
                                                    </td>
                                                    <td width="30%">
                                                        <input name="description[]" class="form-control small_input_box" type="text" placeholder="Description" value="{{ old('description')[$key] ?: '' }}">
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control small_input_box amount" name="amount[]" placeholder="0.00" onkeyup="show_total(this)" value="{{ old('amount')[$key] }}">
                                                    </td>
                                                    <td>

                                                    </td>
                                                </tr>
                                                @endforeach

                                                @else
                                                <tr id="addr_debit_table0">
                                                    <td width="30%">
                                                        <select name="chart_of_account_ids[]" class="form-control small_input_box" onchange="show_debit_balance(this)">
                                                            <option value="">- Select Chart Of Account -</option>
                                                            @foreach($data['chart_of_accounts'] as $id => $name)
                                                            <option value="{{ $id }}">
                                                                {{ $name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control small_input_box debit_balance" readonly="readonly" name="debit_balance[]">
                                                    </td>
                                                    <td width="30%">
                                                        <input name="description[]" class="form-control small_input_box" type="text" placeholder="Description">
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control small_input_box amount" name="amount[]" placeholder="0.00" onkeyup="show_total(this)">
                                                    </td>
                                                    <td>

                                                    </td>
                                                </tr>
                                                @endif
                                    </tbody>

                                </table>
                            </div>

                        </div>

                        <div class="mt-2"></div>

                        <div class="row clearfix">
                            <div class="col-md-12">
                                <div class="pull-right">
                                    <th>
                                        <span class="fa fa-plus btn btn-sm btn-success outer small_input_button addRow" data-repeater-create id="add_row_debit_table"></span>
                                        <span class="fa fa-trash btn btn-sm btn-danger outer small_input_button btnRemove" id="delete_row_debit_table"></span>
                                    </th>
                                </div>
                            </div>
                        </div>

                        <br>
                        <br>



                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-success pull-right" type="submit">
                                <i class="fa fa-check-circle"></i> Save
                            </button>
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

<script>
    // $(() => {
    //     $('.select_1').addClass('small_input_box');
    // });
</script>

<script type="text/javascript">
    // $('.select_1').select2();
</script>

<script type="text/javascript">

    function show_total() {
        var total_debit_amount = 0;

        $('.amount').each(function(){
            var debit_amount = $(this).val();
            total_debit_amount += parseFloat(debit_amount) > 0 ? parseFloat(debit_amount):0;
        });

        $('#credit_amount').val(total_debit_amount);
    }

    function show_credit_balance(element){
        var id = element.value;

        $.ajax({
            url: '{{ url("api/chart_of_accounts/get_chart_data") }}',
            type: 'GET',
            data: 'id='+id,
            success:function(res){
                $('#credit_balance').val(res['balance']);
            }
        });
    }

    function show_debit_balance(element){
        var row = $(element).closest('tr');
        var id = element.value;

        $.ajax({
            url: '{{ url("api/chart_of_accounts/get_chart_data") }}',
            type: 'GET',
            data: 'id='+id,
            success:function(res){
                row.find('.debit_balance').val(res['balance']);
            }
        });
    }


</script>


<script type="text/javascript">


var i = 1;

$('.addRow').on('click', function() {
    addRow('debit_table');
});


function addRow(tableId) {
    i++;

    var table = document.getElementById(tableId);
    var tr = '<tr>' +
        '<td><select class=\"form-control select_' + i + '\" name=\"chart_of_account_ids[]\" onchange=\"show_debit_balance(this)\"><option value=\"\">- Select Chart Of Account -</option> @foreach($data["chart_of_accounts"] as $id => $name)\n' +
        '                                                        <option value="{{ $id }}">{{ $name }}</option>\n' +
        '                                                    @endforeach</select></td>' +
        '<td><input type=\"number\" class=\"form-control small_input_box debit_balance\" readonly=\"readonly\" name=\"debit_balance[]\"></td>' +
        '<td><input type=\"text\" min=\"0\" step=\"any\" name=\"description[]\" id=\"description\" class=\"form-control small_input_box description changesNo\" autocomplete=\"off\" placeholder=\"Description\" onkeyup=\"mul()\"></td>' +
        '<td><input type=\"number\" class=\"form-control small_input_box amount\" onkeyup=\"show_total(this)\" name=\"amount[]\" placeholder=\"0.00\"></td>' +
        '<td><span class=\"btn btn-sm btn-danger remove delete small_input_button fa fa-trash-o\" title="Delete this row"></span></td>' +
        '</tr>';
    $('#debit_table tbody').append(tr);

    $('.select_' + i).select2();

};

$('tbody').on('click', '.remove', function() {
    $(this).parent().parent().remove();
    show_total();
});

</script>





@endsection
