@extends('admin.master')
@section('title','Account Information')
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


        {{-- Add New Modal--}}
        <div class="modal fade" id="addnew" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add New @yield('title')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>



                    <form class="form-horizontal" action="{{ route('account-information.store') }}" method="POST">
                        @csrf

                        <div class="modal-body">
                            <div class="form-group row add_asterisk">
                                <label for="account_name" class="control-label col-md-4">Account Name  </label>
                                <div class="col-md-8">
                                    <input type="text" id="account_name" name="account_name" value="{{ old('account_name') }}" class="form-control @error('account_name') is-invalid @enderror" placeholder="Account Name">

                                    @error('account_name')
                                    <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                                    @enderror

                                </div>
                            </div>

                            <div class="form-group row add_asterisk">
                                <label for="account_no" class="control-label col-md-4">Account No </label>
                                <div class="col-md-8">
                                    <input id="account_no" name="account_no" value="{{ old('account_no') }}" type="text" class="form-control @error('account_no') is-invalid @enderror" placeholder="Account No">

                                    @error('account_no')
                                    <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                                    @enderror

                                </div>
                            </div>

                            <div class="form-group row add_asterisk">
                                <label for="branch_name" class="control-label col-md-4">Branch Name</label>
                                <div class="col-md-8">
                                    <input id="branch_name" name="branch_name" value="{{ old('branch_name') }}" type="text" class="form-control @error('branch_name') is-invalid @enderror" placeholder="Branch Name">

                                    @error('branch_name')
                                    <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                                    @enderror

                                </div>
                            </div>

                            <div class="form-group row add_asterisk">
                                <label for="status" class="control-label col-md-4">Status </label>
                                <div class="col-md-8">
                                    <div class="animated-radio-button" id="status">
                                        <label>
                                            <input type="radio" name="status" value="1" checked><span class="label-text">Published</span>
                                        </label>
                                        &nbsp;
                                        &nbsp;
                                        &nbsp;
                                        <label>
                                            <input type="radio" name="status" value="0"><span class="label-text">Unpublished</span>
                                        </label>
                                    </div>
                                </div>
                            </div>




                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-check-circle"></i> Save
                            </button>
                        </div>

                    </form>



                </div>
            </div>
        </div>



        {{--List Table--}}

        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <button class="btn btn-primary" style="float: right;" data-toggle="modal" data-target="#addnew"><i class="fa fa-plus"></i>Add New</button>
                    <h3 class="tile-title"><i class="fa fa-list"></i> &nbsp; List</h3>

                    <br>
                    <div class="tile-body">
                        <table class="table table-hover table-bordered" id="sampleTable">
                            <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Account Name</th>
                                <th>Account No</th>
                                <th>Branch Name</th>
                                <th>Status</th>
                                {{-- <th>Created At</th>
                                <th>Updated At</th> --}}
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($accountInfos as $key => $accountInfo)

                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $accountInfo->account_name }}</td>
                                    <td>{{ $accountInfo->account_no }}</td>
                                    <td>{{ $accountInfo->branch_name }}</td>
                                    <td>
                                        @if ($accountInfo->status == 1)
                                            <span class="badge badge-success">Published</span>
                                        @else
                                            <span class="badge badge-danger">Un-Published</span>
                                        @endif
                                    </td>
                                    {{-- <td>{{ $accountInfo->created_at->format('F d, Y  h:i s A') }}</td>
                                    <td>{{ $accountInfo->updated_at->format('F d, Y  h:i s A') }}</td> --}}
                                    <td>
                                        <button class="btn btn-info btn-sm" title="Edit" data-toggle="modal" data-target="#edit_{{ $accountInfo->id }}">
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                        </button>

                                        <button class="btn btn-success btn-sm" title="View Details" data-toggle="modal" data-target="#view_{{ $accountInfo->id }}">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </button>

                                        <button class="btn btn-danger btn-sm" title="Delete" onclick="formSubmit('{{ $accountInfo->id }}')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>

                                    <form action="{{ route('account-information.destroy',$accountInfo->id) }}" id="deleteForm_{{ $accountInfo->id }}" method="POST">
                                        @csrf
                                        @method("DELETE")
                                    </form>

                                </tr>

                                <div class="modal fade" id="edit_{{ $accountInfo->id }}" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Edit @yield('title')</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>

                                            <form class="form-horizontal" action="{{ route('account-information.update',$accountInfo->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')

                                                <div class="modal-body">
                                                    <div class="form-group row add_asterisk">
                                                        <label for="account_name" class="control-label col-md-4">Account Name </label>
                                                        <div class="col-md-8">
                                                            <input type="text" id="account_name" name="account_name" value="{{ $accountInfo->account_name ?: old('account_name') }}" class="form-control @error('account_name') is-invalid @enderror" placeholder="Account Name">

                                                            @error('account_name')
                                                            <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                            @enderror

                                                        </div>
                                                    </div>

                                                    <div class="form-group row add_asterisk">
                                                        <label for="account_no" class="control-label col-md-4">Account No </label>
                                                        <div class="col-md-8">
                                                            <input id="account_no" name="account_no" value="{{ $accountInfo->account_no ?: old('account_no') }}" type="text" class="form-control @error('account_no') is-invalid @enderror" placeholder="Account No">

                                                            @error('account_no')
                                                            <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                                                            @enderror

                                                        </div>
                                                    </div>

                                                    <div class="form-group row add_asterisk">
                                                        <label for="branch_name" class="control-label col-md-4">Branch Name </label>
                                                        <div class="col-md-8">
                                                            <input id="branch_name" name="branch_name" value="{{ $accountInfo->branch_name ?: old('branch_name') }}" type="text" class="form-control @error('branch_name') is-invalid @enderror" placeholder="Branch Name">

                                                            @error('branch_name')
                                                            <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                                                            @enderror

                                                        </div>
                                                    </div>

                                                    <div class="form-group row add_asterisk">
                                                        <label for="status" class="control-label col-md-4">Status </label>
                                                        <div class="col-md-8">
                                                            <div class="animated-radio-button" id="status">
                                                                <label>
                                                                    <input type="radio" name="status" value="1" {{ $accountInfo->status == 1 ? 'checked' : '' }} ><span class="label-text">Published</span>
                                                                </label>
                                                                &nbsp;
                                                                &nbsp;
                                                                &nbsp;
                                                                <label>
                                                                    <input type="radio" name="status" value="0" {{ $accountInfo->status == 0 ? 'checked' : '' }}><span class="label-text">Unpublished</span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>




                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fa fa-check-circle"></i> Update
                                                    </button>
                                                </div>

                                            </form>

                                        </div>
                                    </div>
                                </div>


                                <div class="modal fade" id="view_{{ $accountInfo->id }}" tabindex="-1" role="dialog"
                                     aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">@yield('title') Details</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>

                                            <form class="form-horizontal">
                                                <div class="modal-body">

                                                    <div class="col-md-10 offset-1">
                                                        <div class="row">


                                                            <dt class="col-sm-4">Account Name</dt>
                                                            <dd class="col-sm-8">{{ $accountInfo->account_name }}</dd>

                                                            <dt class="col-sm-4">Account No</dt>
                                                            <dd class="col-sm-8">{{ $accountInfo->account_no }}</dd>

                                                            <dt class="col-sm-4">Branch Name</dt>
                                                            <dd class="col-sm-8">{{ $accountInfo->branch_name }}</dd>

                                                            <dt class="col-sm-4">status</dt>
                                                            <dd class="col-sm-8">
                                                                @if ($accountInfo->status == 1)
                                                                    <span class="badge badge-success">Published</span>
                                                                @else
                                                                    <span class="badge badge-danger">Un-Published</span>
                                                                @endif
                                                            </dd>

                                                            <dt class="col-sm-4">Created At</dt>
                                                            <dd class="col-sm-8">
                                                                {{ $accountInfo->created_at->format('F d, Y  h:i s A') }}
                                                            </dd>

                                                            <dt class="col-sm-4">Updated At</dt>
                                                            <dd class="col-sm-8">
                                                                {{ $accountInfo->updated_at->format('F d, Y  h:i s A') }}
                                                            </dd>


                                                        </div>
                                                    </div>

                                                </div>


                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                        Close
                                                    </button>

                                                </div>

                                            </form>

                                        </div>
                                    </div>
                                </div>

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