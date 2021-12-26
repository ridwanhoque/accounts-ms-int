@extends('admin.master')
@section('page_title', 'Chart of Account Tree') 
@section('content')

<main class="app-content">
  <div class="app-title">
    <div>
      <h1><i class="fa fa-th-list"></i> @yield('page_title')</h1>
      <p>@yield('page_title') </p>
    </div>
    <ul class="app-breadcrumb breadcrumb side">
      <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
      <li class="breadcrumb-item">@yield('page_title')</li>
      <li class="breadcrumb-item active"><a href="#">@yield('page_title') Table</a></li>
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
        <h3 class="tile-title">@yield('page_title')  </h3>
        <div class="tile-body">
          <table class="table table-hover table-bordered" id="">
            <thead>
                <tr>
                  <th>Chart of Account</th>
                  <th>Parent</th>
                  <th>Account Code</th>
                </tr>
              </thead>
              <tbody>
                  @foreach ($chart_tree as $item)
                  <tr>
                      <td>{{ $item->head_name }}</td>
                      <td>{{ $item->parent->head_name ?? '' }}</td>
                      <td>{{ $item->account_code ?? '' }}</td>
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


<script type="text/javascript">
  $('#sampleTable').DataTable();
</script>

<script>
  function formSubmit(id) {
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
        $('#deleteForm_' + id).submit();
        swal("Deleted!", "Your data has been deleted.", "success");
      } else {
        swal("Cancelled", "Your data is safe :)", "error");
      }
    });
  }
</script>


@include('admin.includes.delete_confirm')

@endsection