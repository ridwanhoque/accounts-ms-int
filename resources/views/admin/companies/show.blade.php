@extends('admin.master')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i> Manage Company</h1>
                <p>Manage Company Form</p>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Company</li>
                <li class="breadcrumb-item"><a href="#">Show Company</a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <a href="{{route('companies.index')}}" class="btn btn-primary pull-right" style="float: right;"><i
                                class="fa fa-eye"></i>View Company</a>
                    <h3 class="tile-title">Company</h3>
                    <table class="table table-bordered">

                        <tbody>



                        <tr>
                            <th>Company Name</th>
                            <td>{{ $company->name }}</td>
                        </tr>
                        <tr>
                            <th>Company Phone</th>
                            <td>{{ $company->phone }}</td>
                        </tr>

                        <tr>
                            <th>Company Email</th>
                            <td>{{ $company->email }}</td>
                        </tr>
                        <tr>
                            <th>Company Website</th>
                            <td>{{ $company->website }}</td>
                        </tr>
                        <tr>
                            <th>Company Address</th>
                            <td>{{ $company->address }}</td>
                        </tr>
                        <tr>
                            <th>Company Logo</th>
                            <td>
                                @if ($company->company_logo == 'default.jpg')
                                    <img src="{{ asset('uploads/default.jpg') }}" height="94px" width="190px" class="img-thumbnail" alt="">
                                @else
                                    <img src="{{ asset('uploads/company/'.$company->company_logo) }}" height="94px" width="190px" class="img-thumbnail" alt="">
                                @endif
                            </td>
                        </tr>



                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>

    <script>
        function confirmDelete(){
            var cnf=confirm('Are you sure?');
            if(cnf){
                $('#deleteForm').submit();
                return true;
            }else{
                return false;
            }
        }
    </script>

    <!-- Data table plugin-->
    <script type="text/javascript" src="{{ asset('assets/admin/js/plugins/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/admin/js/plugins/dataTables.bootstrap.min.js') }}"></script>
    <script type="text/javascript">$('#sampleTable').DataTable();</script>

@endsection