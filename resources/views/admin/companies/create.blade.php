@extends('admin.master')
@section('content')



    <main class="app-content">
        <form class="form-horizontal" method="POST" action="{{ route('companies.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="app-title">
                <div>
                    <h1><i class="fa fa-edit"></i> Company Information</h1>
                    <p>Create Color Form</p>
                </div>
                <ul class="app-breadcrumb breadcrumb">
                    <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                    <li class="breadcrumb-item">Colors</li>
                    <li class="breadcrumb-item"><a href="#">Add Company</a></li>
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

                        <a href="{{route('companies.index')}}" class="btn btn-primary pull-right" style="float: right;"><i
                                    class="fa fa-eye"></i>View Company</a>
                        <h3 class="tile-title">Add New Company</h3>

                        <div class="tile-body">



                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">Company Name</label>
                                <div class="col-md-8">
                                    <input name="name" value="{{ old('name') }}" class="form-control" type="text" placeholder="Company Name">
                                    <div class="text-danger">{{ $errors->has('name') ? $errors->first('name'):'' }}</div>
                                </div>
                            </div>

                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">Company Phone Number</label>
                                <div class="col-md-8">
                                    <input name="phone" value="{{ old('phone') }}" class="form-control" type="text" placeholder="Company Phone Number">
                                    <div class="text-danger">{{ $errors->has('phone') ? $errors->first('phone'):'' }}</div>
                                </div>
                            </div>



                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">Company Email</label>
                                <div class="col-md-8">
                                    <input name="email" value="{{ old('email') }}" class="form-control" type="email" placeholder="Company Email">
                                    <div class="text-danger">{{ $errors->has('email') ? $errors->first('email'):'' }}</div>
                                </div>
                            </div>

                            <div class="form-group row ">
                                <label class="control-label col-md-3">Company Website</label>
                                <div class="col-md-8">
                                    <input name="website" value="{{ old('website') }}" class="form-control" type="text" placeholder="Company Website">
                                    <div class="text-danger">{{ $errors->has('website') ? $errors->first('website'):'' }}</div>
                                </div>
                            </div>





                            <div class="form-group row">
                                <label class="control-label col-md-3">Company Address</label>
                                <div class="col-md-8">
                                    <textarea name="address" id="" cols="30" rows="6" class="form-control" placeholder="Company Address">{{ old('address') }}</textarea>
                                    <div class="text-danger">{{ $errors->has('address') ? $errors->first('address'):'' }}</div>
                                </div>
                            </div>


                            <div class="form-group row add_asterisk">
                                <label class="control-label col-md-3">Company Logo</label>
                                <div class="col-md-8">
                                    <input type="file" class="form-control" name="company_logo" onchange="readURL(this);">
                                    <div class="text-danger">{{ $errors->has('company_logo') ? $errors->first('company_logo'):'' }}</div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3"></label>
                                <div class="col-md-8">
                                    <div class="img-responsive">
                                        <img id="blah" src="" height="94px" width="190px" class="img-thumbnail" alt="">
                                    </div>

                                </div>
                            </div>




                        </div>



                        <div class="tile-footer">
                            <div class="row">
                                <div class="col-md-12">
                                    <button class="btn btn-primary pull-right" type="submit" type="submit"><i
                                                class="fa fa-fw fa-lg fa-check-circle"></i>Add Company</button>
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


    <script type="text/javascript">
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah')
                        .attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>


@endsection