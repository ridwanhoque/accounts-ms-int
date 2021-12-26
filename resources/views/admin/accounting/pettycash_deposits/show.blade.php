@extends('admin.master')
@section('content')
<main class="app-content">
  <div class="app-title">
    <div>
      <h1><i class="fa fa-edit"></i> Manage Pettycash Deposit</h1>
      <p>Manage Pettycash Deposit Form</p>
    </div>
    <ul class="app-breadcrumb breadcrumb">
      <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
      <li class="breadcrumb-item">Pettycash Deposits</li>
      <li class="breadcrumb-item"><a href="#">Show Pettycash Deposit</a></li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="tile">
        <h3 class="tile-title">Pettycash Deposits Table</h3>
        <table class="table table-bordered">

          <tbody>
            <tr>
              <th>Date</th>
              <td>{{ $pettycashDeposit->pettycash_deposit_date }}</td>
            </tr>
            <tr>
              <th>Received By</th>
              <td>{{ $pettycashDeposit->received_by_user->name }}</td>
            </tr>
            <tr>
              <th>Paid By</th>
              <td></td>
            </tr>
            <tr>
              <th>Paid From</th>
              <td></td>
            </tr>
            <tr>
              <th>Amount</th>
              <td>{{ $pettycashDeposit->amount }}</td>
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
<script type="text/javascript">
  $('#sampleTable').DataTable();
</script>

@endsection