@extends('admin.master')
@section('content')

    <main class="app-content">
        <form class="form-horizontal" method="POST" action="{{ url('users/change_password') }}">
            @csrf

            <input type="hidden" name="id" value="{{ Auth::user()->id }}">

            <div class="app-title">
                <div>
                    <h1><i class="fa fa-edit"></i> Change Password</h1>
                    <p>Update User Information</p>
                </div>
                <ul class="app-breadcrumb breadcrumb">
                    <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                    <li class="breadcrumb-item">Colors</li>
                    <li class="breadcrumb-item"><a href="#">Change Password</a></li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">


                    @if(Session::get('error'))
                        <div class="alert alert-danger">
                            {{ Session::get('error') }}
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

                        <h3 class="tile-title">Change Password</h3>
                        <hr>

                        <div class="tile-body">





                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">New Password</label>
                                <div class="col-md-8">
                                    <input name="password" class="form-control" type="password" placeholder="New Password">
                                    <div class="text-danger">{{ $errors->has('password') ? $errors->first('password'):'' }}</div>
                                </div>
                            </div>

                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">Confirm Password</label>
                                <div class="col-md-8">
                                    <input name="password_confirmation" class="form-control" type="password" placeholder="Confirm Password">
                                    <div class="text-danger">{{ $errors->has('password_confirmation') ? $errors->first('password_confirmation'):'' }}</div>
                                </div>
                            </div>



                        </div>



                        <div class="tile-footer">
                            <div class="row">
                                <div class="col-md-12">
                                    <button class="btn btn-primary pull-right" type="submit" type="submit"><i
                                                class="fa fa-fw fa-lg fa-check-circle"></i>Change Password</button>
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