@extends('admin.master')
@section('content')

    <main class="app-content">
        <form class="form-horizontal" method="POST" action="{{ url('users/update') }}">
            @csrf

            <input type="hidden" name="id" value="{{ Auth::user()->id }}">

            <div class="app-title">
                <div>
                    <h1><i class="fa fa-edit"></i> User Information</h1>
                    <p>Update User Information</p>
                </div>
                <ul class="app-breadcrumb breadcrumb">
                    <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                    <li class="breadcrumb-item">Colors</li>
                    <li class="breadcrumb-item"><a href="#">Edit User Information</a></li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">


                    @if(Session::get('error_message'))
                        <div class="alert alert-danger">
                            {{ Session::get('error_message') }}
                        </div>
                    @endif

                    @if(Session::get('massage'))
                        <div class="alert alert-success">
                            {{ Session::get('massage') }}
                        </div>
                    @endif


                    @if($errors->any())
                        <div class="alert-danger">
                            <ul class="list-unstyled">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="tile">

                        <h3 class="tile-title">Edit User Info</h3>
                        <hr>

                        <div class="tile-body">



                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">User Name</label>
                                <div class="col-md-8">
                                    <input name="name" value="{{ old('name') ?: Auth::user()->name }}" class="form-control" type="text" placeholder="User Name">
                                    <div class="text-danger">{{ $errors->has('name') ? $errors->first('name'):'' }}</div>
                                </div>
                            </div>

                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">User Email</label>
                                <div class="col-md-8">
                                    <input name="email" value="{{ old('email') ?: Auth::user()->email }}" class="form-control" type="text" placeholder="User Email">
                                    <div class="text-danger">{{ $errors->has('email') ? $errors->first('email'):'' }}</div>
                                </div>
                            </div>

                        </div>



                        <div class="tile-footer">
                            <div class="row">
                                <div class="col-md-12">
                                    <button class="btn btn-primary pull-right" type="submit" type="submit"><i
                                                class="fa fa-fw fa-lg fa-check-circle"></i>Update</button>
                                </div>
                            </div>
                        </div>



                    </div>


                </div>

            </div>
            </div>
            </div>

        </form>
    </main>

@endsection