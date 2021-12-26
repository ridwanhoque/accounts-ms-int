@extends('admin.master')
@section('title','Voucher List')
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

    @if(Session::get('message'))
        <div class="alert alert-success">
            {{ Session::get('message') }}
        </div>
    @endif



    {{--List Table--}}

    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <a class="btn btn-primary" style="float: right;"
                    href="{{ route('voucher.create') }}?type={{ request()->type }}"><i class="fa fa-plus"></i>Add New
                    {{ request()->type == 'debit' ? 'Debit' : 'Credit' }} Voucher</a>
                <h3 class="tile-title"><i class="fa fa-list"></i> &nbsp;
                    {{ request()->type == 'debit' ? 'Debit' : 'Credit' }} Voucher List</h3>

                <br>
                <div class="tile-body">
                    <table class="table table-hover table-bordered" id="sampleTable">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Party</th>
                                <th>Party Phone</th>
                                <th>Voucher Date</th>
                                <th>Type</th>
                                <th>Payable Amount</th>
                                <th>Paid Amount</th>
                                <th>Due Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($vouchers as $key => $voucher)

                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $voucher->party->name }}</td>
                                <td>{{ $voucher->party->phone }}</td>
                                <td>{{ date('F d, Y', strtotime($voucher->voucher_date)) }}</td>
                                <td>{{ $voucher->voucher_type }}</td>
                                <td>{{ $voucher->payable_amount }}</td>
                                <td>{{ $voucher->paid_amount }}</td>
                                <td>{{ $voucher->due_amount }}</td>
                                <td>
                                    @if($voucher->due_amount > 0)
                                    <a href="{{ route('due_payments.create') }}?type={{ request()->type }}&voucherid={{ $voucher->id }}"
                                            class="btn btn-sm btn-success" title="Due Payment">
                                            <i class="fa fa-money"></i>
                                        </a>
                                        @else
                                            <a href="{{ url('accounting/voucher_payments', $voucher->id) }}"
                                                class="btn btn-sm btn-success" title="Payment List">
                                                <i class="fa fa-list"></i>
                                            </a>
                                                @endif

                                    <a href="{{ route('voucher.show',$voucher->id) }}?type={{ request()->type }}"
                                        class="btn btn-sm btn-primary" title="View Details">
                                        <i class="fa fa-eye"></i>
                                    </a>

                                    <button class="btn btn-danger btn-sm" title="Delete"
                                        onclick="formSubmit('{{ $voucher->id }}')">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                                <form action="{{ route('voucher.destroy',$voucher->id) }}"
                                    id="deleteForm_{{ $voucher->id }}" method="POST">
                                    @csrf
                                    @method("DELETE")
                                </form>
                            </tr>

                            @endforeach

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

</main>



<script>
    function formSubmit(id)
        {
            swal({
                title: "Are you sure ?",
                text: "You want to delete this data ?",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it.",
                cancelButtonText: "No, cancel it.",
                closeOnConfirm: false,
                closeOnCancel: true
            }, function(isConfirm) {
                if (isConfirm) {
                    $('#deleteForm_'+id).submit();;
                }
            });
        }
</script>



@endsection