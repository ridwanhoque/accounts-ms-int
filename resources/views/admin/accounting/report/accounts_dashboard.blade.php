@extends('admin.master')
@section('title','Accounts Dashboard')

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
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12">
                    <div class="tile">
                        <div class="tile-title">
                            Available Amount 
                        </div>

                        <div class="tile-body">
                            <table class="table no-spacing">
                                <thead>
                                    <tr>
                                        <th>Particulars</th>
                                        <th class="text-right">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($account_balances as $account_balance)
                                    <tr>
                                        <td>{{ $account_balance->account_information->account_name }}</td>
                                        <td class="text-right">{{ $account_balance->sum_amount }}</td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td>Petty cash</td>
                                        <td class="text-right">{{ $pettycash_expenses }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <table class="table no-spacing">
                                <thead>
                                    <tr>
                                        <th>Dues</th>
                                        <th class="text-right">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Recievable</td>
                                        <td class="text-right">{{ $receivable_amount }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="tile">
                        <div class="tile-title">
                            Liabilities : {{ $payable_amount }}
                        </div>

                        <div class="tile-body">
                            <table class="table no-spacing">
                                <tr>
                                    <th>Payable Dues</th>
                                    <td class="text-right">{{ $payable_amount }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12">
                        <div class="tile">
                                <div class="tile-title">
                                    Accounts Summary : {{ date('Y-m-d') }}
                                </div>
                                <div class="tile-body">
                                    <table class="table no-spacing text-center">
                                        <thead>
                                            <tr>
                                                <th>Income</th>
                                                <th>Expense</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ $income_total }}</td>
                                                <td>{{ $expense_total*(-1) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                
                            </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                        <div class="tile">
                                <div class="tile-title">
                                    Month of {{ $this_month_year }}
                                </div>
                                <div class="tile-body">
                                    <table class="table no-spacing text-center">
                                        <thead>
                                            <tr>
                                                <th>Income</th>
                                                <th>Expense</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ $income_this_month }}</td>
                                                <td>{{ $expense_total*(-1) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                
                            </div>
                </div>
            </div>
        </div>
    </div>

</main>


@endsection


@section('js')
<script type="text/javascript" src="{{ asset('assets/admin/js/plugins/bootstrap-datepicker.min.js') }}"></script>
<script>
    $('.date').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            todayHighlight: true
        });
</script>
@stop