@extends('admin.master')
@section('content')
<main class="app-content">
        <div class="app-title">
          <div>
            <h1><i class="fa fa-edit"></i> Manage Pettycash Head</h1>
            <p>Manage Asset Head Form</p>
          </div>
          <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item">Asset Heads</li>
            <li class="breadcrumb-item"><a href="#">Show Asset Head</a></li>
          </ul>
        </div>
        <div class="row">
<div class="col-md-12">
          <div class="tile">
            <h3 class="tile-title">Asset Heads Table</h3>
            <table class="table table-bordered">
              
              <tbody>
                <tr>
                  <th>Asset Head Name</th>
                  <td>{{ $assetChart->name }}</td>
                </tr>
                <tr>
                  <th>Description</th>
                  <td>
                    {{ $assetChart->description }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        </div>
</main>

<script>
function confirmDelete(){
  var cnf=confirm('Are you sure?');
  if(cnf){
      $('#deleteForm').submit();
      return true;
  }else{
    return false;
  }
}
</script>
    
<!-- Data table plugin-->
<script type="text/javascript" src="{{ asset('assets/admin/js/plugins/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/admin/js/plugins/dataTables.bootstrap.min.js') }}"></script>
<script type="text/javascript">$('#sampleTable').DataTable();</script>

        @endsection