@extends('admin.master')

@section('content')
<main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-file-text-o"></i> Invoice</h1>
          <p>Contra Voucher Invoice</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="#">Invoice</a></li>
        </ul>
      </div>
      <div class="row print-area">
        <div class="col-md-12">
          <div class="tile">
            <section class="invoice">
              <div class="row mb-4">
                <div class="col-6">
                  <h2 class="page-header"><i class="fa fa-globe"></i> {{ $contra_voucher->company->name }} </h2>
                </div>
                <div class="col-6">
                  <h5 class="text-right">Date: {{ $contra_voucher->contra_date }}</h5><br>
                </div>
              </div>
              {{-- <div class="row invoice-info">
                <div class="col-6">
                  From <address><strong>{{ $contra_voucher->chart_of_account->head_name ?? '' }}</strong><br>
                    Address : {{ $contra_voucher->chart_of_account->address }}<br>
                    Phone : {{ $contra_voucher->chart_of_account->phone }}<br>
                    Email: {{ $contra_voucher->chart_of_account->email }}
                  </address>
                </div>
                <div class="col-6">To
                  <address><strong>{{ $contra_voucher->company->name }}</strong><br>
                    Address :  {{ $contra_voucher->company->address }}<br>
                    Phone : {{ $contra_voucher->company->phone }}<br>
                    Email: {{ $contra_voucher->company->email }}
                  </address>  
                </div>
              </div> --}}
              <div class="row">
                <div class="col-12 table-responsive">
                  <table class="table no-spacing">
                    <thead>
                      <tr>
                        <th>Order ID : {{ $contra_voucher->id }}</th>
                        <th colspan="2"></th>
                      </tr>
                      <tr>
                        <th>Chart Of Account</th>
                        <th>Description</th>
                        <th class="text-right">Amount</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($contra_voucher->contra_voucher_details as $details)
                      <tr>
                        <td>{{ $details->chart_of_account->head_name ?? '' }}</td>
                        <td>{{ $details->description }}</td>
                        <td class="text-right">{{ $details->amount }}</td>
                      </tr>
                      @endforeach
                      <tr>
                        <td colspan="3" style="text-align:right">Total : <strong>{{ $contra_voucher->amount }} {{ config('app.tk') }}</strong></td>
                      </tr>
                      <tr>
                        <td colspan="3">&nbsp;</td>
                      </tr>
                      <tr>
                        <td> <b> {{ $contra_voucher->chart_of_account->head_name ?? '' }} </b><br> <br><br> <hr>Signature and Date</td>
                        <td>&nbsp;</td>
                        <td style="text-align:right"> <b>Issued By : {{ $contra_voucher->company->name }} </b> <br> <br> <br> <hr> Signature and Date</td>
                      </tr>
                      <tr></tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="row d-print-none mt-2">
                <div class="col-12 text-right"><a class="btn btn-primary" href="javascript:window.print();" ><i class="fa fa-print"></i> Print</a></div>
              </div>
            </section>
          </div>
        </div>
      </div>
    </main>
    @endsection