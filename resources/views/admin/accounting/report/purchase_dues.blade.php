@extends('admin.master')
@section('content')


    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-th-list"></i> Payable Dues</h1>
                <p>Company information </p>
            </div>
            <ul class="app-breadcrumb breadcrumb side">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Payable Dues</li>
                <li class="breadcrumb-item active"><a href="#">Payable Dues Table</a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                @if(Session::get('massage'))
                    <div class="alert alert-success">
                        {{ Session::get('massage') }}
                    </div>
                @endif

                @if(Session::get('message'))
                    <div class="alert alert-success">
                        {{ Session::get('message') }}
                    </div>
                @endif

                <div class="tile">
{{--                    <a href="{{ route('companies.create') }}" class="btn btn-primary" style="float: right;"><i class="fa fa-plus"></i>Add Company</a>--}}
                    <h3 class="tile-title">Company List </h3>
                    <div class="tile-body">
                        <table class="table table-hover table-bordered" id="">
                            <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Supplier</th>
                                <th>Payable Amount</th>
                                <th>Paid Amount</th>
                                <th>Due Amount</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($purchase_dues as $key => $purchase_due)

                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $purchase_due->supplier->name ?? '-' }}</td>
                                    <td>{{ $purchase_due->phone }}</td>
                                    <td>{{ $purchase_due->email }}</td>
                                    <td>{{ $purchase_due->address }}</td>
                                </tr>

                            @endforeach

                            </tbody>
                        </table>

                        {{ $purchase_dues->links() }}
                    </div>
                </div>
            </div>
        </div>
    </main>


    <script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>
    <!-- Data table plugin-->
    <script type="text/javascript" src="{{ asset('assets/admin/js/plugins/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/admin/js/plugins/dataTables.bootstrap.min.js')}}"></script>

    <script type="text/javascript" src="{{ asset('assets/admin/js/plugins/sweetalert.min.js')}}"></script>


    <script type="text/javascript">$('#sampleTable').DataTable();</script>

    <script>
        function formSubmit(id)
        {
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this data !",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel plz!",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function(isConfirm) {
                if (isConfirm) {
                    $('#deleteForm_'+id).submit();
                    swal("Deleted!", "Your data has been deleted.", "success");
                } else {
                    swal("Cancelled", "Your data is safe :)", "error");
                }
            });
        }
    </script>


    @include('admin.includes.delete_confirm')



@endsection