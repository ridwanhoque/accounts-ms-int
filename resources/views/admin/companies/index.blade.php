@extends('admin.master')
@section('content')


    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-th-list"></i> Company Information</h1>
                <p>Company information </p>
            </div>
            <ul class="app-breadcrumb breadcrumb side">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Company Information</li>
                <li class="breadcrumb-item active"><a href="#">Company Information Table</a></li>
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
                                <th>Company Name</th>
                                <th>Company Phone Number</th>
                                <th>Company Email</th>
                                <th>Company Website</th>
                                <th>Company Address</th>
                                <th>Company Logo</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($companies as $key => $company)

                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $company->name }}</td>
                                    <td>{{ $company->phone }}</td>
                                    <td>{{ $company->email }}</td>
                                    <td>{{ $company->website }}</td>
                                    <td>{{ $company->address }}</td>
                                    <td>
                                        @if ($company->company_logo == 'default.jpg')
                                            <img src="{{ asset('uploads/'.$company->company_logo) }}" alt="{{ $company->name }}" width="100px">
                                            @else
                                            <img src="{{ asset('uploads/company/'.$company->company_logo) }}" alt="{{ $company->name }}" width="100px">
                                        @endif

                                    </td>
                                    <td>

                                        <a class="btn btn-info btn-sm" title="Edit" href="{{ route('companies.edit',$company->id) }}"> <i class="fa fa-edit"></i> </a>
                                        <a class="btn btn-primary btn-sm" title="View" href="{{ route('companies.show',$company->id) }}"> <i class="fa fa-eye"></i> </a>
                                        <a class="btn btn-danger btn-sm" title="Delete" onclick="formSubmit('{{ $company->id }}')" href="#"> <i class="fa fa-trash"></i> </a>


                                        <form action="{{route('companies.destroy', $company->id)}}" id="deleteForm_{{ $company->id }}" method="POST">
                                            @csrf
                                            @method("DELETE")
                                        </form>

                                    </td>
                                </tr>

                            @endforeach

                            </tbody>
                        </table>

                        {{ $companies->links() }}
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