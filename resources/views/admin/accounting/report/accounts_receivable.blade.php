@extends('admin.master')
@section('title','Accounts Receivable')

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
                <div class="tile-title"> @yield('title')                </div>

                <div class="tile-body">


                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th width="60%">Particular</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total_this_month = 0;
                                $total_previous_month = 0;
                            @endphp
                            @foreach ($receivable_amounts as $key => $this_month_receivable)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $this_month_receivable->party->name }}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="2" class="text-right"><strong>Total</strong></td>
                                <td><strong>{{ number_format($total_this_month, 2, '.', '') }}</strong></td>
                                <td><strong>{{ number_format($total_previous_month, 2, '.', '') }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
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