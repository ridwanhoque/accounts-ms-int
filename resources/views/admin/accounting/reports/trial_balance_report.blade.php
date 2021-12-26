@extends('admin.master')
@section('title','Trial Balance Report')

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
                <button class="close" type="button" data-dismiss="alert">�</button>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (Session::get('error'))
            <div class="alert alert-dismissible alert-danger">
                <button class="close" type="button" data-dismiss="alert">�</button>
                <strong>Error !</strong> {{ Session::get('error') }}
            </div>
        @endif



        <!-- <div class="row">
                <div class="col-md-12">
                    <div class="tile">
                        <h3 class="tile-title"><i class="fa fa-search"></i> &nbsp; Search Your Report</h3>
                        <br>
                        <div class="tile-body">
                            <form class="row" action="{{ url('report/income-expense/filter/') }}" method="get">
                                <div class="form-group col-md-5">
                                    <label class="control-label">Start Date</label>
                                    <input class="form-control date" type="text" name="start_date" value="" placeholder="Start Date" autocomplete="off">
                                </div>
                                <div class="form-group col-md-5">
                                    <label class="control-label">End Date </label>
                                    <input class="form-control date" type="text" name="end_date" value="" placeholder="End Date" autocomplete="off">
                                </div>
                                <div class="form-group col-md-2 align-self-end">
                                    <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Search</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
        </div> -->


        <div class="row">

            <div class="col-md-12">

                <div class="row">
                        <div class="col-md-6">

                                <div class="tile" style="height: 100%">
                                        <div class="tile-body">
                                                 
                                            <h3>Incomes</h3>
                                                        <table class="table table-hover table-bordered" id="chartOfAccountTable__">
                                                            <thead>
                                                            <tr>
                                                                <th>Chart Of Account</th>
                                                                <th>Balance</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
            
                                                            @foreach($incomes as $chart)
                                                                <tr>
                                                                    <td>
                                                                        <a href="{{ url('/reports/trial_balance', $chart->id) }}">{{ $chart->head_name ?? '' }}</a>
                                                                    </td>
                                                                    <td>{{ Formatter::addComma($chart->balance) }}</td>
                                                                </tr>
                                                            @endforeach
            
                                                            </tbody>
                                                        </table>
            
                                                    <h3>Liabilities</h3>
                                                    <table class="table table-hover table-bordered" id="chartOfAccountTable__">
                                                        <thead>
                                                        <tr>
                                                            <th>Chart Of Account</th>
                                                            <th>Balance</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
            
                                                        @foreach($liabilities as $chart)
                                                            <tr>
                                                                <td>
                                                                    <a href="{{ url('/reports/trial_balance', $chart->id) }}">{{ $chart->head_name ?? '' }}</a>
                                                                </td>
                                                                <td>{{ Formatter::addComma($chart->balance) }}</td>
                                                            </tr>
                                                        @endforeach
            
                                                        </tbody>
                                                    </table>
                                            
                                        </div>
            
                                </div>
                            </div>
                            {{-- col-md-6 ends here --}}
                            
            
            
            
                            <div class="col-md-6">
            
                                <div class="tile" style="height: 100%">
                                       <div class="tile-body">
                                                 
                                            <h3>Expense</h3>
                                                        <table class="table table-hover table-bordered" id="chartOfAccountTable__">
                                                            <thead>
                                                            <tr>
                                                                <th>Chart Of Account</th>
                                                                <th>Balance</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
            
                                                            @foreach($expenses as $chart)
                                                                <tr>
                                                                    <td>
                                                                        <a href="{{ url('/reports/trial_balance', $chart->id) }}">{{ $chart->head_name ?? '' }}</a>
                                                                    </td>
                                                                    <td>{{ Formatter::addComma($chart->balance) }}</td>
                                                                </tr>
                                                            @endforeach
            
                                                            </tbody>
                                                        </table>
            
                                                    <h3>Assets</h3>
                                                    <table class="table table-hover table-bordered" id="chartOfAccountTable__">
                                                        <thead>
                                                        <tr>
                                                            <th>Chart Of Account</th>
                                                            <th>Balance</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
            
                                                        @foreach($assets as $chart)
                                                            <tr>
                                                                <td>
                                                                    <a href="{{ url('/reports/trial_balance', $chart->id) }}">{{ $chart->head_name ?? '' }}</a>
                                                                </td>
                                                                <td>{{ Formatter::addComma($chart->balance) }}</td>
                                                            </tr>
                                                        @endforeach
            
                                                        </tbody>
                                                    </table>
                                            
                                        </div>
            
                                </div>
                            </div>
                            {{-- col-md-6 ends here --}}

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
<!-- Data table plugin-->
<script type="text/javascript" src="{{ asset('assets/admin/js/plugins/jquery.dataTables.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('assets/admin/js/plugins/dataTables.bootstrap.min.js')}}"></script>
    

<script type="text/javascript">$('#chartOfAccountTable').DataTable();</script>

@stop