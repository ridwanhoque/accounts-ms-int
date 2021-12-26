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
            <ul class="list-unstyled">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="row">
            <div class="col-md-12">

                <div class="tile">
                    <a href="{{ route('payment_vouchers.index') }}" class="btn btn-primary" style="float: right;"><i class="fa fa-list"></i> Voucher List</a>
                    <h3 class="tile-title"><i class="fa fa-plus"></i> &nbsp; Add New @yield('title')</h3>
                    <hr>
                    <div class="tile-body">

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="row">
                                    
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group form-inline">
                                                    <label class="pr-3">Date</label>
                                                    <input id="payment_date" name="payment_date" readonly value="{{ date('Y-m-d') }}" type="text" class="form-control small_input_box @error('payment_date') is-invalid @enderror" placeholder="Payment Date">

                                                    @error('payment_date')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div> 
                                            </div>
                                        </div>  
                                    </div>

                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group form-inline">
                                                    <label class="pr-3">Reference</label>
                                                    <input id="ref_id" name="ref_id" value="{{ old('ref_id') }}" type="text" class="form-control small_input_box @error('ref_id') is-invalid @enderror" placeholder="Reference">

                                                    @error('ref_id')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group form-inline">
                                                    <label class="pr-3">Note</label>
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
                        </div>
                        <br>
                        {{-- <div class="row">
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
                        </div> --}}

                        <div class="row">

                                <div class="col-md-10 offset-md-1">
                                    <table class="no-spacing" id="credit_table">
                                        <tr>
                                            <th>Chart of Account</th>
                                            <th>Balance</th>
                                            <th>Description</th>
                                            <th>Credit</th>
                                            <th></th>
                                        </tr>
    
                                        @if(old('credit_chart_ids'))
    
                                        @foreach(old('credit_chart_ids') as $key => $credit_chart_id)
                                        <tr>
                                            <td>
                                                <select name="credit_chart_ids[]" class="form-control select_1" onchange="show_credit_balance(this)">
                                                    <option value="">- Select Chart Of Account -</option>
                                                    @foreach($data['bank_cash_charts'] as $id => $name)
                                                    <option value="{{ $id }}" {{ old('credit_chart_ids')[$key] == $id ? 'selected':'' }}>
                                                        {{ $name }}</option>
                                                    @endforeach
    
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control small_input_box credit_balance" readonly="readonly" name="credit_balance[]" value="{{ old('credit_balance')[$key] }}">
                                            </td>
                                            <td width="30%">
                                                <input name="credit_description[]" class="form-control small_input_box" type="text" placeholder="Description" value="{{ old('credit_description')[$key] ?: '' }}">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control small_input_box credit_amount" name="credit_amount[]" placeholder="0.00" onkeyup="show_total_credit(this)" value="{{ old('credit_amount')[$key] }}">
                                            </td>
                                            <td>
    
                                            </td>
                                        </tr>
                                        @endforeach
    
                                        @else
                                        <tr>
                                            <td width="30%">
                                                <select name="credit_chart_ids[]" class="form-control select_1 small_input_box" onchange="show_credit_balance(this)">
                                                    <option value="">- Select Chart Of Account -</option>
                                                    @foreach($data['bank_cash_charts'] as $id => $name)
                                                    <option value="{{ $id }}">
                                                        {{ $name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control small_input_box credit_balance" readonly="readonly" name="credit_balance[]">
                                            </td>
                                            <td width="30%">
                                                <input name="credit_description[]" class="form-control small_input_box" type="text" placeholder="Description">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control small_input_box credit_amount" name="credit_amount[]" placeholder="0.00" onkeyup="show_total_credit(this)">
                                            </td>
                                            <td>
    
                                            </td>
                                        </tr>
                                        @endif
    
                                    </table>
                                </div>
    
                            </div>
    
                            <div class="row">
                                <div class="col-md-10 offset-md-1">
                                    <div class="col-md-12">
                                        <span class="fa fa-plus btn btn-sm btn-success pull-right pointer small_input_button addRowCredit" id="add_row"></span>
                                    </div>
                                </div>
                            </div>
    
                        <div class="row">

                            <div class="col-md-10 offset-md-1">
                                <table class="no-spacing" id="debit_table">
                                    <tr>
                                        <th>Chart of Account</th>
                                        <th>Balance</th>
                                        <th>Description</th>
                                        <th>Debit</th>
                                        <th></th>
                                    </tr>

                                    @if(old('debit_chart_ids'))

                                    @foreach(old('debit_chart_ids') as $debit_key => $debit_chart_id)
                                    <tr>
                                        <td>
                                            <select name="debit_chart_ids[]" class="form-control select_1" onchange="show_debit_balance(this)">
                                                <option value="">- Select Chart Of Account -</option>
                                                @foreach($data['chart_of_accounts'] as $k => $chart)
                                                <option value="{{ $chart->id }}" {{ old('debit_chart_ids')[$debit_key] == $chart->id ? 'selected':'' }}>
                                                    {{ $chart->head_name }}</option>
                                                @endforeach

                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control small_input_box debit_balance" readonly="readonly" name="debit_balance[]" value="{{ old('debit_balance')[$debit_key] }}">
                                        </td>
                                        <td width="30%">
                                            <input name="debit_description[]" class="form-control small_input_box" type="text" placeholder="Description" value="{{ old('debit_description')[$debit_key] ?: '' }}">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control small_input_box debit_amount" name="debit_amount[]" placeholder="0.00" onkeyup="show_total_debit(this)" value="{{ old('debit_amount')[$debit_key] }}">
                                        </td>
                                        <td>

                                        </td>
                                    </tr>
                                    @endforeach

                                    @else
                                    <tr>
                                        <td width="30%">
                                            <select name="debit_chart_ids[]" class="form-control select_1 small_input_box" onchange="show_debit_balance(this)">
                                                <option value="">- Select Chart Of Account -</option>
                                                
                                                @foreach($data['chart_of_accounts'] as $chart)
                                                <option value="{{ $chart->id }}">
                                                    {{-- {{  $chart->tire > 0 ? ('|'.str_repeat('_', $chart->tire)):'' }}
                                                    {{ $chart->account_code.'-'.$chart->head_name }} --}}
                                                    {{ $chart->head_name ?? '' }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control small_input_box debit_balance" readonly="readonly" name="debit_balance[]">
                                        </td>
                                        <td width="30%">
                                            <input name="debit_description[]" class="form-control small_input_box" type="text" placeholder="Description">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control small_input_box debit_amount" name="debit_amount[]" placeholder="0.00" onkeyup="show_total_debit(this)">
                                        </td>
                                        <td>

                                        </td>
                                    </tr>
                                    @endif

                                </table>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-10 offset-md-1">
                                <div class="col-md-12">
                                    <span class="fa fa-plus btn btn-sm btn-success pull-right pointer small_input_button addRow" id="add_row"></span>
                                </div>
                            </div>
                        </div>


                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                    <table class="no-spacing pull-right">
                                            <tr>
                                                <td>Total Credit</td>
                                                <td>
                                                    <input type="number" class="form-control small_input_box" id="total_credit_amount" name="total_credit_amount" value="{{ old('total_credit_amount') }}" readonly="readonly">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Total Debit</td>
                                                <td>
                                                    <input type="number" class="form-control small_input_box" id="total_debit_amount" name="total_debit_amount" value="{{ old('total_debit_amount') }}" readonly="readonly">
                                                </td>
                                            </tr>
                                        </table>
                            </div>
                        </div>

                    </div>
                    <hr>
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
    $(() => {
        $('.select_1').addClass('small_input_box');
    });
</script>

<script type="text/javascript">
    $('.select_1').select2();
</script>

<script type="text/javascript">

    function show_total_credit() {
        var total_credit_amount = 0;
        
        $('.credit_amount').each(function(){
            var credit_amount = $(this).val();
            total_credit_amount += parseFloat(credit_amount) > 0 ? parseFloat(credit_amount):0;
        });

        $('#total_credit_amount').val(total_credit_amount);
    }

    
    function show_credit_balance(element){
        var row = $(element).closest('tr');
        var id = element.value;

        $.ajax({
            url: '{{ url("api/chart_of_accounts/get_chart_data") }}',
            type: 'GET',
            data: 'id='+id,
            success:function(res){
                row.find('.credit_balance').val(res['balance']);
            }
        });
    }
    
    function show_total_debit() {
        var total_debit_amount = 0;
        
        $('.debit_amount').each(function(){
            var debit_amount = $(this).val();
            total_debit_amount += parseFloat(debit_amount) > 0 ? parseFloat(debit_amount):0;
        });

        $('#total_debit_amount').val(total_debit_amount);
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


{{-- credit table --}}
<script>
     
        var i = 1;
   
   $('.addRowCredit').on('click', function() {
       addRowCredit('parts_table');
   });
   
//    $('form').bind("keypress", function(e) {
//        if (e.keyCode == 13) {
//            e.preventDefault();
//            return false;
//        }
//    });
   
   
//    window.addEventListener("keydown", checkKeypress, false);
   
//    function checkKeypress(key) {
//        if (key.keyCode == "13") {
//            addRowCredit('parts_table');
//        }
//    }
   
   
   function addRowCredit(tableId) {
       i++;
   
       var table = document.getElementById(tableId);
       var tr = '<tr>' +
           '<td><select class=\"form-control select_' + i + '\" name=\"credit_chart_ids[]\" onchange=\"show_credit_balance(this)\"><option value=\"\">- Select Chart Of Account -</option> @foreach($data["bank_cash_charts"] as $id => $name)\n' +
           '                                                        <option value="{{ $id }}">{{ $name }}</option>\n' +
           '                                                    @endforeach</select></td>' +
           '<td><input type=\"number\" class=\"form-control small_input_box credit_balance\" readonly=\"readonly\" name=\"credit_balance[]\"></td>' +
           '<td><input type=\"text\" min=\"0\" step=\"any\" name=\"credit_description[]\" id=\"credit_description\" class=\"form-control small_input_box description changesNo\" autocomplete=\"off\" placeholder=\"Description\" onkeyup=\"mul()\"></td>' +
           '<td><input type=\"number\" class=\"form-control small_input_box credit_amount\" onkeyup=\"show_total_credit(this)\" name=\"credit_amount[]\" placeholder=\"0.00\"></td>' +
           '<td><span class=\"btn btn-sm btn-danger removeCredit delete small_input_button fa fa-trash-o\" title="Delete this row"></span></td>' +
           '</tr>';
       $('#credit_table tbody').append(tr);
   
       $('.select_' + i).select2();
   
   };
   
   $('tbody').on('click', '.removeCredit', function() {
       $(this).parent().parent().remove();
       show_total_credit();
   });
   </script>
   
   



<script type="text/javascript">
    
    
    var j = 1;

    $('.addRow').on('click', function() {
        addRow('parts_table');
    });

   


    function addRow(tableId) {
        j++;

        var table = document.getElementById(tableId);
        var tr = '<tr>' +
            '<td><select class=\"form-control select_' + j + '\" name=\"debit_chart_ids[]\" onchange=\"show_debit_balance(this)\"><option value=\"\">- Select Chart Of Account -</option> @foreach($data["chart_of_accounts"] as $id => $name)\n' +
            '                                                        <option value="{{ $id }}">{{ $name }}</option>\n' +
            '                                                    @endforeach</select></td>' +
            '<td><input type=\"number\" class=\"form-control small_input_box debit_balance\" readonly=\"readonly\" name=\"debit_balance[]\"></td>' +
            '<td><input type=\"text\" min=\"0\" step=\"any\" name=\"debit_description[]\" id=\"debit_description\" class=\"form-control small_input_box description changesNo\" autocomplete=\"off\" placeholder=\"Description\" onkeyup=\"mul()\"></td>' +
            '<td><input type=\"number\" class=\"form-control small_input_box debit_amount\" onkeyup=\"show_total_debit(this)\" name=\"debit_amount[]\" placeholder=\"0.00\"></td>' +
            '<td><span class=\"btn btn-sm btn-danger remove delete small_input_button fa fa-trash-o\" title="Delete this row"></span></td>' +
            '</tr>';
        $('#debit_table tbody').append(tr);

        $('.select_' + j).select2();

    };

    $('tbody').on('click', '.remove', function() {
        $(this).parent().parent().remove();
        show_total_debit();
    });

</script>



@endsection