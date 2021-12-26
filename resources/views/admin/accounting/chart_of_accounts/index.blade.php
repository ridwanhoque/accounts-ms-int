@extends('admin.master')
@section('content')
@section('page_title', 'Chart Of Account')
<main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-th-list"></i> @yield('page_title') Information</h1>
          <p>@yield('page_title') information </p>
        </div>
        <ul class="app-breadcrumb breadcrumb side">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item">@yield('page_title') Information</li>
          <li class="breadcrumb-item active"><a href="#">@yield('page_title') Information Table</a></li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
            @if(Session::get('message'))
            <div class="alert alert-success">
              {{ Session::get('message') }}
            </div>
            @endif
          
          <div class="tile">
            <a href="{{ route('chart_of_accounts.create') }}" class="btn btn-primary" style="float: right;"><i class="fa fa-plus"></i>Add @yield('page_title')</a>
            <h3 class="tile-title">@yield('page_title') List </h3>
            <div class="tile-body">
                                  
            <table class="table table-hover table-bordered" id="chartOfAccountTable">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Type</th>
                                <th>Head Name</th>
                                <th>Parent Head</th>
                                <th>Tire</th>
                                <th>Balance</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($chartOfAccounts as $key => $chartOfAccount)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $chartOfAccount->chart_type->name ?? '' }}</td>
                                <td>{{ $chartOfAccount->head_name }}</td>
                                <td>{{ $chartOfAccount->parent ? $chartOfAccount->parent->head_name:'' }}</td>
                                <td>{{ $chartOfAccount->tire }}</td>
                                <td>{{ $chartOfAccount->balance }}</td>
                                <td>
                                  <a class="btn btn-info btn-sm" title="Edit" href="{{ route('chart_of_accounts.edit',$chartOfAccount->id) }}"> <i class="fa fa-edit"></i> </a>
                                  <a class="btn btn-primary btn-sm" title="View" href="{{ route('chart_of_accounts.show',$chartOfAccount->id) }}"> <i class="fa fa-eye"></i> </a>
                                  <a class="btn btn-danger btn-sm" title="Delete" onclick="formSubmit('{{$chartOfAccount->id}}')" href="#"> <i class="fa fa-trash"></i> </a>
        
                                  <form action="{{route('chart_of_accounts.destroy', $chartOfAccount->id)}}" id="deleteForm_{{$chartOfAccount->id}}" method="POST">
                                      @csrf
                                      @method("DELETE")
                                  </form>
                                </td>
                               
      

                            </tr>
                                @endforeach
                  
                </tbody>
              </table>
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
    

    <script type="text/javascript">$('#chartOfAccountTable').DataTable();</script>

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