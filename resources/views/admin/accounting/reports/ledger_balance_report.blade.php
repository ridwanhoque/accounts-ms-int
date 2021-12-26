@extends('admin.master')
@section('title','Balance Sheet Report')

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
                <div class="tile">
                    <h3 class="tile-title"><i class="fa fa-list"></i> &nbsp; Ledger List</h3>

                    <br>
                    <div class="tile-body">
                        <table class="table table-hover table-bordered">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Chart Of Account</th>
                                <th>Opening Balance</th>
                                <th>Debit</th>
                                <th>Credit</th>
                                <th>Closing Balance</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($ledgers as $ledger)
                                <tr>
                                    <td>{{ $ledger->date }}</td>
                                    <td>{{ $ledger->chart_of_account->head_name ?? '' }}</td>
                                    <td>{{ Formatter::addComma($ledger->opening_balance) }}</td>
                                    <td>{{ Formatter::addComma($ledger->debit_amount) }}</td>
                                    <td>{{ Formatter::addComma($ledger->credit_amount) }}</td>
                                    <td>{{ Formatter::addComma($ledger->closing_balance) }}</td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
						{{ $ledgers->links() }}
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