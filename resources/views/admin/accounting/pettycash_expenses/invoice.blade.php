@extends('admin.master')

@section('content')
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-file-text-o"></i> Invoice</h1>
            <p>Pettycash Expense Invoice</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">Invoice</a></li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <section class="invoice">
                    <div class="row mb-4">
                        <div class="col-6">
                            <h2 class="page-header"><i class="fa fa-globe"></i> {{ $pettycashExpense->company->name }}
                            </h2>
                        </div>
                        <div class="col-6">
                            <h5 class="text-right">Date: {{ $pettycashExpense->pettycash_expense_date }}</h5><br>
                        </div>
                    </div>
                    <div class="row invoice-info">
                        <div class="col-6">
                            <strong>INVOICE # {{ $pettycashExpense->id }}</strong>
                        </div>
                        <div class="col-6">Invoice By
                            <address><strong>{{ $pettycashExpense->company->name }}</strong><br>
                                Address : {{ $pettycashExpense->company->address }}<br>
                                Phone : {{ $pettycashExpense->company->phone }}<br>
                                Email: {{ $pettycashExpense->company->email }}
                            </address>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="25%">Pettycash Head</th>
                                        <th width="55%">Purpose</th>
                                        <th width="20%">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pettycashExpense->pettycash_expense_details as $details)
                                    <tr>
                                        <td>{{ $details->pettycash_chart->name }}</td>
                                        <td>{{ $details->purpose }}</td>
                                        <td>{{ $details->amount }}</td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="3" style="text-align:right"><strong>Total : </strong>
                                            {{ $pettycashExpense->total_amount }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="pull-right text-center">
                                <tr height="60px">
                                    <th>
                                        Accounts
                                    </th>
                                </tr>
                                <tr class="spacer-7">
                                    <td>
                                        _______________________________
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        sign and date
                                    </td>
                                </tr>
                                {{-- <tr>
                                    <td> <b> Accounts : </b><br> <br><br>
                                        <hr>Signature and Date</td>
                                    <td>&nbsp;</td>
                                    <td style="text-align:right"> <b>Issued By : {{ $pettycashExpense->company->name }}
                                        </b> <br> <br> <br>
                                        <hr> Signature and Date</td>
                                </tr>
                                <tr></tr> --}}
                            </table>
                        </div>
                    </div>
                    <div class="row d-print-none mt-2">
                        <div class="col-12 text-right"><a class="btn btn-primary" href="javascript:window.print();"
                                ><i class="fa fa-print"></i> Print</a></div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</main>
@endsection