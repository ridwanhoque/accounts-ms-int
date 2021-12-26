@extends('admin.master')
@section('title','Buyer')
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



                <form class="form-horizontal" action="{{ route('party.store') }}" method="POST">
                    <input type="hidden" name="party_type" value="1">
                    @csrf

                    <div class="modal-body">
                        <div class="form-group row add_asterisk">
                            <label class="control-label col-md-4">Type</label>

                            <div class="col-md-8">
                                <select name="vendor_type" class="form-control">
                                    <option value="1">Corporate</option>
                                    <option value="2">Retail</option>
                                </select>

                                @error('vendor_type')
                                <div class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row add_asterisk">
                            <label for="name" class="control-label col-md-4">Name </label>
                            <div class="col-md-8">
                                <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" placeholder="Name">

                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror

                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="opening_balance" class="control-label col-md-4">Opening Balance </label>
                            <div class="col-md-8">
                                <input type="number" id="opening_balance" name="opening_balance" value="{{ old('opening_balance') }}" class="form-control @error('opening_balance') is-invalid @enderror" placeholder="Opening Balance">

                                @error('opening_balance')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror

                            </div>
                        </div>



                        <div class="form-group row">
                            <label for="email" class="control-label col-md-4">Email </label>
                            <div class="col-md-8">
                                <input id="email" name="email" value="{{ old('email') }}" type="text" class="form-control @error('email') is-invalid @enderror" placeholder="Email">

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror

                            </div>
                        </div>



                        <div class="form-group row">
                            <label for="phone" class="control-label col-md-4">Phone </label>
                            <div class="col-md-8">
                                <input id="phone" name="phone" value="{{ old('phone') }}" type="text" class="form-control @error('phone') is-invalid @enderror" placeholder="Phone">

                                @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror

                            </div>
                        </div>

                        <div class="form-group row ">
                            <label for="address" class="control-label col-md-4">Address </label>
                            <div class="col-md-8">
                                <textarea id="address" name="address" class="form-control @error('address') is-invalid @enderror" placeholder="Address">{{ old('address') }}</textarea>

                                @error('address')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror

                            </div>
                        </div>


                        <strong> Contact Person</strong>

                        <div class="form-group row">
                            <label for="name" class="control-label col-md-4">Name </label>
                            <div class="col-md-8">
                                <input type="text" id="contact_person_name" name="contact_person_name" value="{{ old('name') }}" class="form-control @error('contact_person_name') is-invalid @enderror" placeholder="Contact Person Name">

                                @error('contact_person_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror

                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="name" class="control-label col-md-4">Designation </label>
                            <div class="col-md-8">
                                <input type="text" id="contact_person_designation" name="contact_person_designation" value="{{ old('contact_person_designation') }}" class="form-control @error('contact_person_designation') is-invalid @enderror" placeholder="Contact Person Designation">

                                @error('contact_person_designation')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror

                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="name" class="control-label col-md-4">Department </label>
                            <div class="col-md-8">
                                <input type="text" id="contact_person_department" name="contact_person_department" value="{{ old('contact_person_department') }}" class="form-control @error('contact_person_department') is-invalid @enderror" placeholder="Contact Person Department">

                                @error('contact_person_department')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror

                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="name" class="control-label col-md-4">Telephone </label>
                            <div class="col-md-8">
                                <input type="text" id="contact_person_telephone" name="contact_person_telephone" value="{{ old('contact_person_telephone') }}" class="form-control @error('contact_person_telephone') is-invalid @enderror" placeholder="Contact Person Telephone">

                                @error('contact_person_telephone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror

                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="name" class="control-label col-md-4">Mobile </label>
                            <div class="col-md-8">
                                <input type="text" id="contact_person_mobile" name="contact_person_mobile" value="{{ old('contact_person_mobile') }}" class="form-control @error('contact_person_mobile') is-invalid @enderror" placeholder="Contact Person Mobile">

                                @error('contact_person_mobile')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror

                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="status" class="control-label col-md-4">Status </label>
                            <div class="col-md-8">
                                <div class="animated-radio-button" id="status">
                                    <label>
                                        <input type="radio" name="status" value="1" checked><span class="label-text">Active</span>
                                    </label>
                                    &nbsp;
                                    &nbsp;
                                    &nbsp;
                                    <label>
                                        <input type="radio" name="status" value="0"><span class="label-text">Inactive</span>
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
                <h3 class="tile-title"><i class="fa fa-list"></i> &nbsp; @yield('title') List</h3>

                <br>
                <div class="tile-body">
                    <table class="table table-hover table-bordered" id="sampleTable">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Opening Balance</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($parties as $key => $party)

                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $party->name }}</td>
                                <td>{{ $party->vendor_type == 1 ? 'Corporate':'Retail' }}</td>
                                <td>{{ $party->opening_balance }}</td>
                                <td>{{ $party->email }}</td>
                                <td>{{ $party->phone }}</td>
                                <td>{{ $party->address }}</td>
                                <td>
                                    @if ($party->status == 1)
                                    <span class="badge badge-success">Published</span>
                                    @else
                                    <span class="badge badge-danger">Un-Published</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-info btn-sm" title="Edit" data-toggle="modal" data-target="#edit_{{ $party->id }}">
                                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                    </button>

                                    <button class="btn btn-success btn-sm" title="View Details" data-toggle="modal" data-target="#view_{{ $party->id }}">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </button>

                                    <button class="btn btn-danger btn-sm" title="Delete" onclick="formSubmit('{{ $party->id }}')">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>

                                <form action="{{ route('party.destroy',$party->id) }}" id="deleteForm_{{ $party->id }}" method="POST">
                                    @csrf
                                    @method("DELETE")
                                </form>

                            </tr>

                            <div class="modal fade" id="edit_{{ $party->id }}" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Edit @yield('title')</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        <form class="form-horizontal" action="{{ route('party.update',$party->id) }}" method="POST">
                                            <input type="hidden" name="party_type" value="1">
                                            @csrf
                                            @method('PUT')

                                            <div class="modal-body">

                                                <div class="form-group row add_asterisk">
                                                    <label class="control-label col-md-4">Type</label>

                                                    <div class="col-md-8">
                                                        <select name="vendor_type" class="form-control">
                                                            <option value="1" {{ $party->vendor_type == 1 ? 'selected':'' }}>Corporate</option>
                                                            <option value="2" {{ $party->vendor_type == 2 ? 'selected':'' }}>Retail</option>
                                                        </select>

                                                        @error('vendor_type')
                                                        <div class="invalid-feedback">
                                                            <strong>{{ $message }}</strong>
                                                        </div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="name" class="control-label col-md-4">Name </label>
                                                    <div class="col-md-8">
                                                        <input type="text" id="name" name="name" value="{{ $party->name ?: old('name') }}" class="form-control @error('name') is-invalid @enderror" placeholder="Name">

                                                        @error('name')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror

                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="opening_balance" class="control-label col-md-4">Opening Balance </label>
                                                    <div class="col-md-8">
                                                        <input type="number" id="opening_balance" name="opening_balance" value="{{ $party->opening_balance ?: old('opening_balance') }}" class="form-control @error('opening_balance') is-invalid @enderror" placeholder="Opening Balance">

                                                        @error('opening_balance')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror

                                                    </div>
                                                </div>




                                                <div class="form-group row">
                                                    <label for="email" class="control-label col-md-4">Email </label>
                                                    <div class="col-md-8">
                                                        <input id="email" name="email" value="{{ $party->email ?: old('email') }}" type="text" class="form-control @error('email') is-invalid @enderror" placeholder="Email">

                                                        @error('email')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror

                                                    </div>
                                                </div>



                                                <div class="form-group row">
                                                    <label for="phone" class="control-label col-md-4">Phone </label>
                                                    <div class="col-md-8">
                                                        <input id="phone" name="phone" value="{{ $party->phone ?: old('phone') }}" type="text" class="form-control @error('phone') is-invalid @enderror" placeholder="Phone">

                                                        @error('phone')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror

                                                    </div>
                                                </div>

                                                <div class="form-group row ">
                                                    <label for="address" class="control-label col-md-4">Address </label>
                                                    <div class="col-md-8">
                                                        <textarea id="address" name="address" class="form-control @error('address') is-invalid @enderror" placeholder="Address">{{ $party->address ?: old('address') }}</textarea>

                                                        @error('address')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror

                                                    </div>
                                                </div>


                                                <strong> Contact Person</strong>

                                                <div class="form-group row">
                                                    <label for="name" class="control-label col-md-4">Name </label>
                                                    <div class="col-md-8">
                                                        <input type="text" id="contact_person_name" name="contact_person_name" value="{{ old('name') ?: $party->contact_person_name }}" class="form-control @error('contact_person_name') is-invalid @enderror" placeholder="Contact Person Name">

                                                        @error('contact_person_name')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror

                                                    </div>
                                                </div>


                                                <div class="form-group row">
                                                    <label for="name" class="control-label col-md-4">Designation
                                                    </label>
                                                    <div class="col-md-8">
                                                        <input type="text" id="contact_person_designation" name="contact_person_designation" value="{{ old('contact_person_designation') ?: $party->contact_person_designation }}" class="form-control @error('contact_person_designation') is-invalid @enderror" placeholder="Contact Person Designation">

                                                        @error('contact_person_designation')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror

                                                    </div>
                                                </div>


                                                <div class="form-group row">
                                                    <label for="name" class="control-label col-md-4">Department </label>
                                                    <div class="col-md-8">
                                                        <input type="text" id="contact_person_department" name="contact_person_department" value="{{ old('contact_person_department') ?: $party->contact_person_department }}" class="form-control @error('contact_person_department') is-invalid @enderror" placeholder="Contact Person Department">

                                                        @error('contact_person_department')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror

                                                    </div>
                                                </div>


                                                <div class="form-group row">
                                                    <label for="name" class="control-label col-md-4">Telephone </label>
                                                    <div class="col-md-8">
                                                        <input type="text" id="contact_person_telephone" name="contact_person_telephone" value="{{ old('contact_person_telephone') ?: $party->contact_person_telephone }}" class="form-control @error('contact_person_telephone') is-invalid @enderror" placeholder="Contact Person Telephone">

                                                        @error('contact_person_telephone')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror

                                                    </div>
                                                </div>


                                                <div class="form-group row">
                                                    <label for="name" class="control-label col-md-4">Mobile </label>
                                                    <div class="col-md-8">
                                                        <input type="text" id="contact_person_mobile" name="contact_person_mobile" value="{{ old('contact_person_mobile') ?: $party->contact_person_mobile }}" class="form-control @error('contact_person_mobile') is-invalid @enderror" placeholder="Contact Person Mobile">

                                                        @error('contact_person_mobile')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror

                                                    </div>
                                                </div>



                                                <div class="form-group row">
                                                    <label for="status" class="control-label col-md-4">Status </label>
                                                    <div class="col-md-8">
                                                        <div class="animated-radio-button" id="status">
                                                            <label>
                                                                <input type="radio" name="status" value="1" {{ $party->status == 1 ? 'checked' : '' }}><span class="label-text">Published</span>
                                                            </label>
                                                            &nbsp;
                                                            &nbsp;
                                                            &nbsp;
                                                            <label>
                                                                <input type="radio" name="status" value="0" {{ $party->status == 0 ? 'checked' : '' }}><span class="label-text">Unpublished</span>
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


                            <div class="modal fade" id="view_{{ $party->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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

                                                <div class="col-md-12">
                                                    <div class="row">


                                                        <dt class="col-md-4">Name</dt>
                                                        <dd class="col-md-8">{{ $party->name }}</dd>

                                                        <dt class="col-md-4">Opening Balance</dt>
                                                        <dd class="col-md-8">{{ $party->opening_balance }}</dd>

                                                        <dt class="col-md-4">Email</dt>
                                                        <dd class="col-md-8">{{ $party->email }}</dd>

                                                        <dt class="col-md-4">Phone</dt>
                                                        <dd class="col-md-8">{{ $party->phone }}</dd>

                                                        <dt class="col-md-4">Address</dt>
                                                        <dd class="col-md-8">{{ $party->address }}</dd>

                                                        <dt class="col-md-4">status</dt>
                                                        <dd class="col-md-8">
                                                            @if ($party->status == 1)
                                                            <span class="badge badge-success">Published</span>
                                                            @else
                                                            <span class="badge badge-danger">Un-Published</span>
                                                            @endif
                                                        </dd>

                                                        <!-- <dt class="col-sm-4">Created At</dt>
                                                        <dd class="col-sm-8">
                                                            {{ $party->created_at->format('F d, Y  h:i s A') }}
                                                        </dd>

                                                        <dt class="col-sm-4">Updated At</dt>
                                                        <dd class="col-sm-8">
                                                            {{ $party->updated_at->format('F d, Y  h:i s A') }}
                                                        </dd> -->


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
    function formSubmit(id) {
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
                $('#deleteForm_' + id).submit();;
            }
        });
    }
</script>




@endsection