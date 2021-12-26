@extends('admin.master')
@section('title','Cash Flow')

@section('content')


<main class="app-content">

    <div class="app-title">
        <div>
            <h1><i class="fa fa-edit"></i> @yield('title')</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">@yield('title')</a></li>
        </ul>
    </div>



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

    @if (Session::get('error'))
    <div class="alert alert-dismissible alert-danger">
        <button class="close" type="button" data-dismiss="alert">×</button>
        <strong>Error !</strong> {{ Session::get('error') }}
    </div>
    @endif


    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-title"> @yield('title') </div>

                <div class="tile-body">
                    <form action="">
                        <table class="table">
                            <tr>
                                <th>Select Account </th>
                                <td width="20%">
                                    <select name="account_information_id" class="form-control select2">
                                        <option value="">Choose</option>
                                        @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}" {{ request()->get('account_information_id') == $account->id ? 'selected':'' }}>{{ $account->account_name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <th>From Date </th>
                                <td><input type="text" name="start_date" class="form-control date"
                                        value="{{ request()->filled('start_date') ?  request()->start_date : $this_month_start }}">
                                </td>
                                <th>To Date </th>
                                <td><input type="text" name="end_date" class="form-control date"
                                        value="{{ request()->filled('end_date') ? request()->end_date : $today }}"></td>
                                <td><button type="submit" value="" class="btn btn-md btn-primary">Submit</button></td>
                            </tr>
                            <tr><td colspan="6" class="text-right"><button id="print_btn" class="btn btn-success">Print</button></td></tr>
                        </table>
                    </form>
                    <table class="text-center print-area" align="center" style="visibility: hidden">
                        <tr>
                            <td><strong>{{ $currentUser->company->name }}</strong></td>
                        </tr>
                        <tr>
                            <td>
                                {{ $currentUser->company->address }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                {{ $currentUser->company->phone }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                {{ $currentUser->company->email }}
                            </td>
                        </tr>
                    </table>
                    <table class="table table-bordered print-area mt-4" id="cash_flow_table">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th width="60%">Particular</th>
                                <th>Debit</th>
                                <th>Credit</th>
                                <th>Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            // $total_this_month = 0;
                            // $total_previous_month = 0;
                            $total_balance = 0;
                            $balance = 0;
                            $i = 0;
                            @endphp
                            @foreach ($cash_flow as $key => $cash_flow_outer)

                            @foreach ($cash_flow_outer->cash_flow as $index => $cash_flow)

                            @php
                            $cash_flow_amount = $cash_flow->sum_amount;
                            @endphp
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $cash_flow->chart_of_account->head_name }}</td>
                                <td class="text-right debit_amounts">
                                    {{ $debit_amount = $cash_flow_amount <0 ? $cash_flow_amount*(-1) : 0 }}</td>
                                <td class="text-right credit_amounts">
                                    {{ $credit_amount = $cash_flow_amount >0 ? $cash_flow_amount : 0 }}</td>
                                <td class="text-right balance">{{ $balance += $credit_amount - $debit_amount }}</td>
                                {{-- <td>{{ $income_statement->head_name }}</td>
                                <td class="this_month_incomes">
                                    @php
                                    $sum_this_income = 0;
                                    @endphp
                                    @foreach ($income_statement->voucher_account_charts as $item)
                                    @php
                                    $sum_this_income += $item->this_month_income['sum_amount']
                                    @endphp
                                    @endforeach
                                    {{ $sum_this_income }}
                                </td>
                                <td class="previous_month_incomes">
                                    @php
                                    $sum_previous_income = 0;
                                    @endphp
                                    @foreach ($income_statement->voucher_account_charts as $item)
                                    @php
                                    $sum_previous_income += $item->previous_month_income['sum_amount']
                                    @endphp
                                    @endforeach
                                    {{ $sum_previous_income }}
                                </td> --}}
                            </tr>

                            @endforeach
                            @endforeach
                            {{-- <strike></strike> --}}
                            <tr>
                                <td colspan="2" class="text-right"><strong>Total</strong></td>
                                <td class="text-right" id="total_debit_amount"></td>
                                <td class="text-right" id="total_credit_amount"></td>
                                <td class="text-right" id="total_balance"></td>
                            </tr>
                        </tbody>
                </div>
                </table>
            </div>



        </div>


    </div>

</main>


@endsection


@section('js')
<script type="text/javascript" src="{{ asset('assets/admin/js/plugins/bootstrap-datepicker.min.js') }}"></script>

<script src="{{ asset('assets/admin/js/plugins/select2.min.js')}}"></script>
<script>
    $('.select2').select2();
    $('.date').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            todayHighlight: true
        });

document.querySelector("#print_btn").addEventListener("click", function() {
	window.print();
});

$(document).ready(function(){
    var total_debit_amount = 0;
    var total_credit_amount = 0;
    var total_balance = 0;

    $('.debit_amounts').each(function(){
        total_debit_amount += parseFloat($(this).html());
        $('#cash_flow_table tbody tr:last td:eq(1)').html('<strong>'+total_debit_amount)+'</strong>';
    });    

    $('.credit_amounts').each(function(){
        total_credit_amount += parseFloat($(this).html());

        $('#cash_flow_table tbody tr:last td:eq(2)').html('<strong>'+total_credit_amount+'</strong>');
    });


    $('.balance').each(function(){
        total_balance += parseFloat($(this).html());

       // $('#cash_flow_table tbody tr:last td:eq(3)').html('<strong>'+total_balance+'</strong>')
    });

})  
</script>
@stop