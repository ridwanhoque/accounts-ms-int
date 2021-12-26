@extends('admin.master')
@section('content')
<main class="app-content">
        <div class="app-title">
          <div>
            <h1><i class="fa fa-edit"></i> Chart Of Account Head</h1>
            <p>Chart Of Account</p>
          </div>
          <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item">Chart Of Account Heads</li>
            <li class="breadcrumb-item"><a href="#">Show Chart Of Account Head</a></li>
          </ul>
        </div>
        <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <h3 class="tile-title">Chart Of Account Table</h3>
            <table class="table table-bordered">
              
              <tbody>
                  <tr>
                    <th>Head Name</th>
                    <td>{{ $chart_of_account->head_name }}</td>
                  </tr>
                  <tr>
                    <th>Parent</th>
                    <td>
                      {{ $chart_of_account->parent->head_name ?? '' }}
                    </td>
                  </tr>
                  <tr>
                    <th>Type</th>
                    <td>{{ $chart_of_account->chart_type->name }}</td>
                  </tr>
                  <tr>
                    <th>Category</th>
                    <td>
                      {{ $chart_of_account->owner_type->name }}
                    </td>
                  </tr>
                  <tr>
                    <th>Opening Balance</th>
                    <td>{{ $chart_of_account->opening_balance }}</td>
                  </tr>
                  <tr>
                    <th>Balance</th>
                    <td>{{ $chart_of_account->balance }}</td>
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