@extends('admin.master')
@section('title','Voucher')
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


            <div class="row">
                <div class="col-md-12">

                    <div class="tile">
                        <a href="{{ route('voucher.index') }}?type={{ request()->type }}" class="btn btn-primary" style="float: right;" ><i class="fa fa-list"></i> Voucher List</a>
                        <h3 class="tile-title"> &nbsp; {{ request()->type == 'debit' ? 'Debit' : 'Credit' }} Voucher</h3>
                        <hr>
                        <div class="tile-body">

                            <table class="table table-bordered">

                                <tr>
                                    <th>Party Name</th>
                                    <td>{{ $voucher->party->name }}</td>
                                </tr>
                                <tr>
                                    <th>Party Phone</th>
                                    <td>{{ $voucher->party->phone }}</td>
                                </tr>
                                <tr>
                                    <th>Voucher Date</th>
                                    <td>{{ date('F d, Y', strtotime($voucher->voucher_date)) }}</td>
                                </tr>
                                <tr>
                                    <th>Voucher Reference</th>
                                    <td>{{ $voucher->voucher_reference }}</td>
                                </tr>
                                <tr>
                                    <th>Payable Amount</th>
                                    <td>{{ number_format($voucher->payable_amount) }}</td>
                                </tr>
                                <tr>
                                    <th>Paid Amount</th>
                                    <td>{{ number_format($voucher->paid_amount) }}</td>
                                </tr>
                                <tr>
                                    <th>Due Amount</th>
                                    <td>{{ number_format($voucher->due_amount) }}</td>
                                </tr>

                            </table>

                        </div>


                    </div>

                </div>
            </div>


    </main>


    <script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/plugins/select2.min.js')}}"></script>

    <script src="{{ asset('assets/admin/js/style.js') }}"></script>

    <script type="text/javascript">
        $('.select2').select2();
    </script>






@endsection