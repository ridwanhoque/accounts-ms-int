@extends('admin.master')
@section('content')


    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i> User Info</h1>
                <p>User Info</p>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">User Info</li>
                <li class="breadcrumb-item"><a href="#">User Info</a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <a href="{{ url('users/list') }}" class="btn btn-primary pull-right" style="float: right;"><i
                                class="fa fa-eye"></i>View User</a>
                    <h3 class="tile-title">User Info Table</h3>
                    <table class="table table-bordered">

                        <tbody>



                        <tr>
                            <th>User Name</th>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <th>User Email</th>
                            <td>{{ $user->email }}</td>
                        </tr>





                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>


@endsection