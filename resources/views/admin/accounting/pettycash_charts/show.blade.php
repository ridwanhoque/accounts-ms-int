@extends('admin.master')
@section('content')
<main class="app-content">
        <div class="app-title">
          <div>
            <h1><i class="fa fa-edit"></i> Manage Pettycash Head</h1>
            <p>Manage Pettycash Head Form</p>
          </div>
          <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item">Pettycash Heads</li>
            <li class="breadcrumb-item"><a href="#">Show Pettycash Head</a></li>
          </ul>
        </div>
        <div class="row">
<div class="col-md-12">
          <div class="tile">
            <h3 class="tile-title">Pettycash Heads Table</h3>
            <table class="table table-bordered">
              
              <tbody>
                <tr>
                  <th>Pettycash Head Name</th>
                  <td>{{ $pettycashHead->name }}</td>
                </tr>
                <tr>
                  <th>Pettycash Head Status</th>
                  <td>
                    <div class="badge badge-{{ $pettycashHead->status == 1 ? 'success':'danger' }}">
                      {{ $statuses[$pettycashHead->status] }}
                    </div>
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