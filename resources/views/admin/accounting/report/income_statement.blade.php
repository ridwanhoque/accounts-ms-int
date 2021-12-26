@extends('admin.master')
@section('title','Income Statement')

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


                    <table class="table table-bordered" id="is_table">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th width="60%">Particular</th>
                                <th>{{ $this_month_name }}</th>
                                <th>{{ $previous_month_name }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $total_this_month = 0;
                            $total_previous_month = 0;
                            @endphp
                            @foreach ($income_statements as $key => $income_statement)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $income_statement->head_name }}</td>
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
                                </td>
                            </tr>
                            @endforeach
                            {{-- <strike></strike> --}}
                            <tr>
                                <td colspan="2" class="text-right"><strong>Total</strong></td>
                                <td></td>
                                <td></td>

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

$(document).ready(function(){
    var total_this_month = 0;
$('.this_month_incomes').each(function(){
    total_this_month += parseFloat($(this).html());
    $('#is_table tbody tr:last td:eq(1)').html(total_this_month);
});

var total_previous_month = 0;
$('.previous_month_incomes').each(function(){
    total_previous_month += parseFloat($(this).html());
    $('#is_table tbody tr:last td:eq(2)').html(total_previous_month);
});
    
})  
</script>
@stop