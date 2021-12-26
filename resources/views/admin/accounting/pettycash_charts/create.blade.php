@extends('admin.master')
@section('content')

<main class="app-content">
  <form class="form-horizontal" method="POST" action="{{ route('pettycash_charts.store') }}"> 
  @csrf

  <div class="app-title">
    <div>
      <h1><i class="fa fa-edit"></i> Pettycash Head Information</h1>
      <p>Create Pettycash Head Form</p>
    </div>
    <ul class="app-breadcrumb breadcrumb">
      <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
      <li class="breadcrumb-item">Pettycash Heads</li>
      <li class="breadcrumb-item"><a href="#">Add Pettycash Head</a></li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      @if(Session::get('error_message'))
      <div class="alert alert-danger">
        {{ Seession::get('error_message') }}
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

        <a href="{{route('pettycash_charts.index')}}" class="btn btn-primary pull-right" style="float: right;"><i
            class="fa fa-eye"></i>View Pettycash Head</a>
        <h3 class="tile-title">Pettycash Head</h3>

        <div class="tile-body">



          <div class="form-group row add_asterisk">
            <label class="control-label col-md-3">Pettycash Head Name</label>
            <div class="col-md-8">
              <input name="name" value="{{ old('name') }}" class="form-control" type="text" placeholder="Pettycash Head Name">
              <div class="text-danger">{{ $errors->has('name') ? $errors->first('name'):'' }}</div>
            </div>
          </div>
          
          <div class="form-group row">
            <label class="control-label col-md-3">Status</label>
            <div class="col-md-8">
              <select name="status" class="form-control">
                @foreach ($statuses as $key => $status)
                    <option value="{{ $key }}">{{ $status }}</option>
                @endforeach
              </select>
              <div class="text-danger">{{ $errors->has('status') ? $error->first('status'):'' }}</div>
            </div>
          </div>


        </div>



        <div class="tile-footer">
          <div class="row">
            <div class="col-md-12">
              <button class="btn btn-primary pull-right" type="submit" type="submit"><i
                  class="fa fa-fw fa-lg fa-check-circle"></i>Add Pettycash Head</button>
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

<script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/plugins/select2.min.js')}}"></script>


@endsection