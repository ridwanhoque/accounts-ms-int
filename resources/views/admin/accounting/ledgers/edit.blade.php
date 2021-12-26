@extends('admin.master')
@section('title','Edit Voucher')
@section('content')

    <main class="app-content">
        <form class="form-horizontal" method="POST" action="{{ route('voucher.update',$voucher->id) }}">
            @csrf
            @method('PUT')

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
                        <h3 class="tile-title"><i class="fa fa-edit"></i>  &nbsp; Edit {{ request()->type == 'debit' ? 'Debit' : 'Credit' }} Voucher</h3>
                        <hr>
                        <div class="tile-body">

                            <div class="row">
                                <div class="col-sm-10 offset-md-1">
                                    <div class="row">
                                        <div class="col-md-5">

                                            <div class="form-group row add_asterisk">
                                                <label for="party_id" class="control-label col-md-4">Pay To (Party)</label>
                                                <div class="col-md-8">
                                                    <input type="hidden" name="voucher_type" value="{{ request()->type }}">

                                                    <select name="party_id" id="party_id" class="form-control @error('party_id') is-invalid @enderror">
                                                        <option value="">- Select Party -</option>
                                                        @foreach($parties as $party)
                                                        <option {{ $voucher->party_id == $party->id ? 'selected' : '' }} value="{{ $party->id }}">{{ $party->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('party_id')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror

                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="ref_id" class="control-label col-md-4">Reference(#ID)</label>
                                                <div class="col-md-8">
                                                    <input id="ref_id" name="ref_id" value="{{ $voucher->voucher_reference ?: old('ref_id') }}" type="text" class="form-control @error('ref_id') is-invalid @enderror" placeholder="Reference(#ID)">

                                                    @error('ref_id')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror

                                                </div>
                                            </div>


                                        </div>
                                        <div class="col-md-6 offset-md-1">

                                            <div class="form-group row add_asterisk">
                                                <label for="demoDate" class="control-label col-md-4">Payment Date</label>
                                                <div class="col-md-8">
                                                    <input id="demoDate" name="payment_date" value="{{ $voucher->voucher_date }}" type="text" class="form-control @error('payment_date') is-invalid @enderror" placeholder="Payment Date">

                                                    @error('method_name')
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
                                    <table class="table table-bordered" id="tab_logic">
                                        <tr>
                                            <th>Select Chart of Account</th>
                                            <th>Description</th>
                                            <th>Payable Amount</th>
                                            <th>Paid Amount</th>
                                            <th>Action</th>
                                        </tr>

                                        @foreach($voucher->voucher_account_charts as $key => $voucher_account_chart)

                                        <tr>
                                            <td width="30%">
                                                <select name="chart_of_account_id[]" id="" class="form-control">
                                                    <option value="">- Select Chart Of Account -</option>
                                                    @foreach($chart_of_accounts as $chart_of_account)
                                                        <option {{ $voucher_account_chart->chart_of_account_id == $chart_of_account->id ? 'selected' : ''  }} value="{{ $chart_of_account->id }}">{{ $chart_of_account->head_name }}</option>
                                                    @endforeach

                                                </select>
                                            </td>
                                            <td width="30%">
                                                <input name="description[]" value="{{ $voucher_account_chart->description }}" class="form-control" type="text" placeholder="Description">
                                            </td>
                                            <td>
                                                <input name="payable_amount_unit[]" value="{{ $voucher_account_chart->payable_amount }}" id="payable_amount_unit_{{ $key+1 }}" class="form-control payable_amount1" type="number" placeholder="Payable" onkeyup="show_payable_sum()">
                                            </td>

                                            <td>
                                                <input name="paid_amount_unit[]" value="{{ $voucher_account_chart->paid_amount }}" id="paid_amount_unit_{{ $key+1 }}" class="form-control paid_amount1" type="number" placeholder="Paid" onkeyup="show_paid_sum(1)">
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-danger remove delete" name="btn" type="button"><span class="fa fa-trash-o"></span> </button>
                                            </td>
                                        </tr>

                                        @endforeach

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


                                    <div class="col-md-5">

                                        <div class="form-group row add_asterisk">
                                            <label for="account_info" class="control-label col-md-4"> Select Account </label>
                                            <div class="col-md-8">
                                                <select name="account_information_id" id="account_info" class="form-control @error('account_information_id') is-invalid @enderror">
                                                    <option value="">- Select Account Information -</option>
                                                    @foreach($account_informations as $account_information)
                                                        <option {{ $voucher->voucher_payment->account_information_id == $account_information->id ? 'selected' : '' }} value="{{ $account_information->id }}">{{ $account_information->account_name }} - {{ $account_information->account_no }}</option>
                                                    @endforeach
                                                </select>
                                                @error('account_information_id')
                                                <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror

                                            </div>
                                        </div>


                                        <div class="form-group row add_asterisk">
                                            <label for="account_info" class="control-label col-md-4"> Select Method </label>
                                            <div class="col-md-8">
                                                <select name="payment_method_id" id="account_info" class="form-control @error('payment_method_id') is-invalid @enderror">
                                                    <option value="">- Select Payment Method -</option>
                                                    @foreach($payment_methods as $payment_method)
                                                        <option {{ $voucher->voucher_payment->payment_method_id == $payment_method->id ? 'selected' : '' }} value="{{ $payment_method->id }}">{{ $payment_method->method_name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('payment_method_id')
                                                <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror

                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label for="cheque_number" class="control-label col-md-4"> Cheque Number </label>
                                            <div class="col-md-8">
                                                <input type="text" name="cheque_number" class="form-control" value="{{ $voucher->cheque_number }}" placeholder="cheque Number">

                                                @error('cheque_number')
                                                <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror

                                            </div>
                                        </div>



                                    </div>

                                    <div class="col-md-6 offset-md-1">

                                        <div class="form-group row add_asterisk">
                                            <label for="account_info" class="control-label col-md-4"> Total Payable Amount </label>
                                            <div class="col-md-8">
                                                <div class="input-group">
                                                    <input class="form-control @error('payable_total_amount') is-invalid @enderror " name="payable_total_amount" readonly id="payable_amount" type="text" value="{{ number_format($voucher->payable_amount) }}">
                                                    <div class="input-group-append"><span class="input-group-text">.tk</span></div>
                                                </div>
                                                @error('payable_total_amount')
                                                <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror

                                            </div>
                                        </div>

                                        <div class="form-group row add_asterisk">
                                            <label for="account_info" class="control-label col-md-4"> Total Paid Amount </label>
                                            <div class="col-md-8">
                                                <div class="input-group">
                                                    <input class="form-control @error('paid_total_amount') is-invalid @enderror " name="paid_total_amount" readonly id="paid_amount" type="text" placeholder="Total Paid Account" value="{{ number_format($voucher->paid_amount) }}">
                                                    <div class="input-group-append"><span class="input-group-text">.tk</span></div>
                                                </div>
                                                @error('paid_total_amount')
                                                <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror

                                            </div>
                                        </div>

                                        <div class="form-group row add_asterisk">
                                            <label for="account_info" class="control-label col-md-4"> Total Due Amount </label>
                                            <div class="col-md-8">
                                                <div class="input-group">
                                                    <input class="form-control @error('due_total_amount') is-invalid @enderror " name="due_total_amount" readonly id="due_amount" type="text" placeholder="Due Account" value="{{ number_format($voucher->due_amount) }}">
                                                    <div class="input-group-append"><span class="input-group-text">.tk</span></div>
                                                </div>
                                                @error('due_total_amount')
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
                        <button class="btn btn-primary" type="submit">
                            <i class="fa fa-edit"></i> Update
                        </button>

                    </div>

                </div>
            </div>

        </form>
    </main>


    <script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/plugins/select2.min.js')}}"></script>

    <script src="{{ asset('assets/admin/js/style.js') }}"></script>

    <script type="text/javascript">
        $('.select2').select2();
    </script>




    <script type="text/javascript">
        $('.addRow').on('click',function () {
            addRow('parts_table');
        });
        var i = 1;
        function addRow(tableId) {
            i++;
            var table = document.getElementById(tableId);
            var tr = '<tr>' +
                '<td><select class=\"form-control\" name=\"chart_of_account_id[]\" id=\"\"><option value=\"\">- Select Chart Of Account -</option> @foreach($chart_of_accounts as $chart_of_account)\n' +
                '                                                        <option value="{{ $chart_of_account->id }}">{{ $chart_of_account->head_name }}</option>\n' +
                '                                                    @endforeach</select></td>'+

                '<td><input type=\"text\" min=\"0\" step=\"any\" name=\"description[]\" id=\"costPerUnit\" class=\"form-control changesNo\" autocomplete=\"off\" placeholder=\"Description\" onkeyup=\"mul()\"></td>'+

                '<td><input type=\"number\" min=\"0\" step=\"any\" name=\"payable_amount_unit[]\" id=\"payable_amount_unit_'+i+'\" class=\"form-control payable_amount1 changesNo\" autocomplete=\"off\" placeholder=\"Payable\" onkeyup=\"show_payable_sum()\"></td>'+


                '<td><input type=\"number\" min=\"0\" step=\"any\" name=\"paid_amount_unit[]\" id=\"paid_amount_unit_'+i+'\"  class=\"form-control paid_amount1\" onkeyup=\"show_paid_sum('+i+')\" placeholder=\"Paid\"></td>'+
                '<td><button class=\"btn btn-sm btn-danger remove delete\" name=\"btn\" type=\"button\" title="Delete this row"><span class=\"fa fa-trash-o\"></span> </button></td>'+
                '</tr>';
            $('tbody').append(tr);
        };
        $('tbody').on('click','.remove', function () {
            $(this).parent().parent().remove();
        });




        function show_due_amount(){

            var due_amount = 0;
            var payable = $("#payable_amount").val();
            var paid = $("#paid_amount").val();
            due_amount = payable-paid;

            $("#due_amount").val(due_amount.toFixed(2));

        }

        function show_payable_sum(){
            $(document).ready(function(){
                $('.payable_amount1').on('keyup' , function(e){
                    var total_payable = 0;
                    $(".payable_amount1").each(function(i, payableamount){
                        var p = $(payableamount).val();
                        total_payable += p ? parseFloat(p):0;
                        $("#payable_amount").val(total_payable);
                    });
                    show_due_amount();

                 });
            });


        }


        function compare_paid_with_payable(n){
            var this_payable = 0;
            this_payable = parseFloat($("#payable_amount_unit_"+n).val());
            var this_paid = 0;
            this_paid = parseFloat($("#paid_amount_unit_"+n).val());


            if(this_paid>this_payable){
                $("#paid_amount_unit_"+n).val(this_payable);
            }

        }

        function show_paid_sum(n){
            $(document).ready(function(){
                compare_paid_with_payable(n);
                var total_paid = 0;
                $(".paid_amount1").each(function(i, paidamount){
                    var p = $(paidamount).val();
                    total_paid += p ? parseFloat(p):0;

                    $("#paid_amount").val(total_paid);

                });

                show_due_amount();

            });

        }
    </script>



@endsection