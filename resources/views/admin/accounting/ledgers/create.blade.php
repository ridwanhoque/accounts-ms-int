@extends('admin.master')
@section('title','Add New')
@section('content')


<main class="app-content">


    <form class="form-horizontal" method="POST" action="{{ route('ledgers.store') }}">
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
                    <a href="{{ route('ledgers.index') }} }}" class="btn btn-primary" style="float: right;"><i class="fa fa-list"></i> Voucher List</a>
                    <h3 class="tile-title"><i class="fa fa-plus"></i> &nbsp; Add New Ledger</h3>
                    <hr>
                    <div class="tile-body">

                        <div class="row col-md-10 offset-md-1">


                            <div class="col-md-3">

                                <div class="form-group row">
                                    <label for="payment_date" class="control-label col-md-4">Date</label>
                                    <div class="col-md-8">
                                        <input id="payment_date" name="payment_date" readonly value="{{ date('Y-m-d') }}" type="text" class="form-control @error('payment_date') is-invalid @enderror" placeholder="Payment Date">

                                        @error('method_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror

                                    </div>
                                </div>

                            </div>

                        </div>

                        <div class="row">
                            <div class="col-sm-10 offset-md-1">
                                <div class="row">


                                    <div class="col-md-3">

                                        <div class="form-group row">
                                            <label for="chart_of_account_id" class="control-label col-md-4">Account</label>
                                            <div class="col-md-8">
                                                <select name="chart_of_account_id" class="form-control select2">
                                                    @foreach($data['chart_of_accounts'] as $id => $name)
                                                    <option value="{{ $id }}" {{ $id == old('chart_of_account_id') ? 'selected':'' }}>
                                                        {{ $name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                            <label for="ref_id" class="control-label col-md-4">Reference(#ID)</label>
                                            <div class="col-md-8">
                                                <input id="ref_id" name="ref_id" value="{{ old('ref_id') }}" type="text" class="form-control @error('ref_id') is-invalid @enderror" placeholder="Reference(#ID)">

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
                                            <label for="note" class="control-label col-md-4">Description</label>
                                            <div class="col-md-8">
                                                <input id="note" name="note" value="{{ old('note') }}" type="text" class="form-control @error('note') is-invalid @enderror" placeholder="Description">

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
                        <br>

                        <div class="row">

                            <div class="col-md-10 offset-md-1">
                                <table class="no-spacing" id="tab_logic">
                                    <tr>
                                        <th width="15%">Debit / Credit</th>
                                        <th>Chart of Account</th>
                                        <th>Description</th>
                                        <th>Amount</th>
                                        <th>Debit</th>
                                        <th>Credit</th>
                                        <th></th>
                                    </tr>

                                    @if(old('chart_of_account_id'))

                                    @foreach(old('chart_of_account_id') as $key => $chart_of_account_id)
                                    <tr>
                                        <td>
                                            <select name="debit_credit" class="form-control debit_credit">
                                                <option value="1">Debit</option>
                                                <option value="2">Credit</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="chart_of_account_ids[]" class="form-control select_1">
                                                <option value="">- Select Chart Of Account -</option>
                                                @foreach($data['chart_of_accounts'] as $id => $name)
                                                <option value="{{ $id }}" {{ $id == $chart_of_account_id ? 'selected':'' }}>
                                                    {{ $name }}</option>
                                                @endforeach

                                            </select>
                                        </td>
                                        <td width="30%">
                                            <input name="description[]" class="form-control" type="text" placeholder="Description" value="{{ old('description')[$key] ?: '' }}">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control amount" name="amount" placeholder="0.00">
                                        </td>
                                        <td>
                                            <input name="debit_amount[]" id="debit_amount_1" readonly="readonly" class="form-control debit_amount1" type="number" placeholder="Dr" onkeyup="show_debit_sum('+ i +')" value="{{ old('debit_amount')[$key] ?: '' }}">
                                        </td>

                                        <td>
                                            <input name="credit_amount[]" id="credit_amount_1" readonly="readonly" class="form-control credit_amount1" type="number" placeholder="Cr" onkeyup="show_credit_sum('+ i +')" value="{{ old('credit_amount')[$key] }}">
                                        </td>
                                        <td>

                                        </td>
                                    </tr>
                                    @endforeach

                                    @else
                                    <tr>
                                        <td>
                                            <select name="debit_credit" class="form-control debit_credit">
                                                <option value="1">Debit</option>
                                                <option value="2">Credit</option>
                                            </select>
                                        </td>
                                        <td width="30%">
                                            <select name="chart_of_account_id[]" class="form-control select_1">
                                                <option value="">- Select Chart Of Account -</option>
                                                @foreach($data['chart_of_accounts'] as $id => $name)
                                                <option value="{{ $id }}">
                                                    {{ $name }}</option>
                                                @endforeach

                                            </select>
                                        </td>
                                        <td width="30%">
                                            <input name="description[]" class="form-control" type="text" placeholder="Description">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control amount" name="amount" placeholder="0.00">
                                        </td>
                                        <td>
                                            <input name="debit_amount[]" id="debit_amount_1" readonly="readonly" class="form-control debit_amount1" type="number" placeholder="Dr" onkeyup="show_debit_sum(1)">
                                        </td>

                                        <td>
                                            <input name="credit_amount[]" id="credit_amount_1" readonly="readonly" class="form-control credit_amount1" type="number" placeholder="Cr" onkeyup="show_credit_sum(1)">
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
                                    <button type="button" class="btn btn-sm btn-success addRow pull-right" title="Add New Row">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>


                        <br>
                        <br>

                        <div class="row">
                            <div class="col-md-10 offset-md-1">
                                <div class="row">



                                    <div class="col-md-6 offset-md-1">

                                        <div class="form-group row">
                                            <label for="account_info" class="control-label col-md-4"> Total Debit </label>
                                            <div class="col-md-8">
                                                <div class="input-group">
                                                    <input class="form-control @error('debit_total_amount') is-invalid @enderror " name="debit_total_amount" readonly id="debit_amount" value="{{ old('debit_total_amount') ?: '' }}" type="number" placeholder="Debit">
                                                    <div class="input-group-append"><span class="input-group-text">.tk</span></div>
                                                </div>
                                                @error('debit_total_amount')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror

                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="account_info" class="control-label col-md-4"> Total Credit
                                            </label>
                                            <div class="col-md-8">
                                                <div class="input-group">
                                                    <input class="form-control @error('credit_total_amount') is-invalid @enderror " name="credit_total_amount" readonly id="credit_amount" type="number" placeholder="Credit" value="{{ old('total_credit_amount') ?: '' }}">
                                                    <div class="input-group-append"><span class="input-group-text">.tk</span></div>
                                                </div>
                                                @error('credit_total_amount')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror

                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="account_info" class="control-label col-md-4"> Balance
                                            </label>
                                            <div class="col-md-8">
                                                <div class="input-group">
                                                    <input class="form-control @error('balance') is-invalid @enderror " name="balance" readonly id="due_amount" type="number" placeholder="Balance" value="{{ old('balance') ?: '' }}">
                                                    <div class="input-group-append"><span class="input-group-text">.tk</span></div>
                                                </div>
                                                @error('balance')
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
    function debit_credit_amount(amount, row) {
        row.find('.debit_amount1').val(0);
        row.find('.credit_amount1').val(0);
        var debit_credit = row.find('.debit_credit').val() == 1 ? '.debit_amount1' : '.credit_amount1';


        row.find(debit_credit).val(amount);
    }

    $('.debit_credit').on('change', function() {
        var row = $(this).closest('tr');
        var amount = row.find('.amount').val() > 0 ? row.find('.amount').val() : 0;

        debit_credit_amount(amount, row);
        show_debit_sum(row);
        show_credit_sum(row);
    });

    $('.amount').on('keyup', function() {

        var amount = $(this).val() > 0 ? $(this).val() : 0;
        var row = $(this).closest('tr');

        debit_credit_amount(amount, row);
        show_debit_sum(row);
        show_credit_sum(row);
    });

    function get_payment_method() {
        var id = document.getElementById("account_info").value;

        $.ajax({
            url: "{{ route('voucher.get-payment-method') }}",
            type: "get",
            data: {
                id: id
            },
            success: function(response) {
                $('#payment_method').html(response['payment_methods']);
                $('#account_balance').val(response['account_balance']);
            },
            error: function(xhr, status) {
                alert('There is some error.Try after some time.');
            }
        });

    }
</script>


<script type="text/javascript">
    var i = 1;

    $('.addRow').on('click', function() {
        addRow('parts_table');
    });

    $('form').bind("keypress", function(e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            return false;
        }
    });


    window.addEventListener("keydown", checkKeypress, false);

    function checkKeypress(key) {
        if (key.keyCode == "13") {
            addRow('parts_table');
        }

    }




    function addRow(tableId) {
        i++;

        var table = document.getElementById(tableId);
        var tr = '<tr>' +
            '<td><select name=\"debit_credit\" class=\"form-control debit_credit\"><option value=\"1\">Debit</option><option value=\"2\">Credit</option></select></td>' +
            '<td><select class=\"form-control select_' + i + '\" name=\"chart_of_account_id[]\" id=\"\"><option value=\"\">- Select Chart Of Account -</option> @foreach($data["chart_of_accounts"] as $id => $name)\n' +
            '                                                        <option value="{{ $id }}">{{ $name }}</option>\n' +
            '                                                    @endforeach</select></td>' +

            '<td><input type=\"text\" min=\"0\" step=\"any\" name=\"description[]\" id=\"costPerUnit\" class=\"form-control changesNo\" autocomplete=\"off\" placeholder=\"Description\" onkeyup=\"mul()\"></td>' +
            '<td><input type=\"number\" class=\"form-control amount\" name=\"amount\" placeholder=\"0.00\"></td>' +

            '<td><input type=\"number\" min=\"0\" step=\"any\" name=\"debit_amount[]\" id=\"debit_amount_' + i + '\" class=\"form-control debit_amount1 changesNo\" autocomplete=\"off\" placeholder=\"Dr\" onkeyup=\"show_debit_sum(' + i + ')\"></td>' +


            '<td><input type=\"number\" min=\"0\" step=\"any\" name=\"credit_amount[]\" id=\"credit_amount_' + i + '\"  class=\"form-control credit_amount1\" onkeyup=\"show_credit_sum(' + i + ')\" placeholder=\"Cr\"></td>' +
            '<td><button class=\"btn btn-sm btn-danger remove delete\" name=\"btn\" type=\"button\" title="Delete this row"><span class=\"fa fa-trash-o\"></span> </button></td>' +
            '</tr>';
        $('tbody').append(tr);

        $('.select_' + i).select2();

    };
    $('tbody').on('click', '.remove', function() {
        $(this).parent().parent().remove();
    });


    // function add_row(n) {
    //     $(document).ready(function(){
    //         $('.credit_amount'+n).each(function () {
    //             addRow('parts_table');
    //          });
    //     });
    // }





    function show_due_amount() {

        var due_amount = 0;
        var debit = $("#debit_amount").val();
        var credit = $("#credit_amount").val();
        due_amount = debit - credit;

        $("#due_amount").val(due_amount.toFixed(2));

    }

    function zero_fill(n) {
        var this_debit = 0;
        this_debit = parseFloat($("#debit_amount_" + n).val());
        var this_credit = 0;
        this_credit = parseFloat($("#credit_amount_" + n).val());


        if (this_credit > 0) {
            $("#debit_amount_" + n).val(0);
        }
        if (this_debit > 0) {
            $("#credit_amount_" + n).val(0);
        }

    }

    function show_debit_sum(n) {
        $(document).ready(function() {

            $('.debit_amount1').each(function(e) {
                var total_debit = 0;
                $(".debit_amount1").each(function(i, debitamount) {
                    var p = $(debitamount).val();
                    total_debit += p ? parseFloat(p) : 0;
                    $("#debit_amount").val(total_debit);
                });
                show_due_amount();

            });

            $('#debit_amount_' + n).val > 0 ? $('#credit_amount_' + n).val('0') : '';

        });


    }



    function show_credit_sum(n) {
        $(document).ready(function() {


            $('.credit_amount1').each(function(e) {
                var total_credit = 0;
                $(".credit_amount1").each(function(i, creditamount) {
                    var p = $(creditamount).val();
                    total_credit += p ? parseFloat(p) : 0;

                    $("#credit_amount").val(total_credit);

                });

                show_due_amount();
            });
            $('#credit_amount_' + n).val > 0 ? $('#debit_amount_' + n).val('0') : '';

        });


    }
</script>






@endsection