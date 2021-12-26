@extends('admin.master')
@section('content')

<main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-th-list"></i> Asset Information</h1>
          <p>Asset information </p>
        </div>
        <ul class="app-breadcrumb breadcrumb side">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item">Asset Information</li>
          <li class="breadcrumb-item active"><a href="#">Asset Information Table</a></li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
            @if(Session::get('message'))
            <div class="alert alert-success">
              {{ Session::get('message') }}
            </div>
            @endif
          @if(Session::get('error_message'))
            <div class="alert alert-danger">
              {{ Session::get('error_message') }}
            </div>
            @endif
          <div class="tile">
            <a href="{{route('assets.create')}}" class="btn btn-primary" style="float: right;"><i class="fa fa-plus"></i>Add Assets</a>
            <h3 class="tile-title">Asset List </h3>
            <div class="tile-body">
              <table class="table table-hover table-bordered" id="">
                <thead>
                  <tr>
                    <th>Date</th>
                    <!-- <th>Asset ID</th> -->
                    <th>Party</th>
                    <th>Total</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($assets as $asset)
                  {{-- @dd(array_column($asset->asset_details->toArray(), 'id')) --}}
                  <tr>
                      <td>{{ $asset->asset_date }}</td>
                      <!-- <td>{{ $asset->id }}</td> -->
                      <td>{{ $asset->party->name }}</td>
                      <td>{{ $asset->total_amount }} {{ config('app.tk') }}</td>
                      <td>
                          
                        <a class="btn btn-primary btn-sm" title="View" href="{{ route('assets.show',$asset->id) }}"> <i class="fa fa-eye"></i> </a> 
                            <a class="btn btn-danger btn-sm" title="Delete" onclick="formSubmit('{{$asset->id}}')" href="#"> <i class="fa fa-trash"></i> </a>
                        
                        
                            <form action="{{route('assets.destroy', $asset->id)}}" id="deleteForm_{{$asset->id}}" method="POST">
                                @csrf
                                @method("DELETE")
                            </form>
                      </td>
                    </tr>                        
                  @endforeach
                  
                </tbody>
              </table>
              {{ $assets->links() }}
            </div>
          </div>
        </div>
      </div>
    </main>


    <script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>
    <!-- Data table plugin-->
    <script type="text/javascript" src="{{ asset('assets/admin/js/plugins/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/admin/js/plugins/dataTables.bootstrap.min.js')}}"></script>
     

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