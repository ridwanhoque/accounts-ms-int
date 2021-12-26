@extends('admin.master')
@section('content')
<main class="app-content">
  <div class="app-title">
    <div>
      <h1><i class="fa fa-edit"></i> Manage Fund Transfer</h1>
      <p>Manage Fund Transfer Form</p>
    </div>
    <ul class="app-breadcrumb breadcrumb">
      <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
      <li class="breadcrumb-item">Fund Transfers</li>
      <li class="breadcrumb-item"><a href="#">Show Fund Transfer</a></li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="tile">
        <h3 class="tile-title">Fund Transfers Table</h3>
        <table class="table table-bordered">

          <tbody>
            <tr>
              <th>Fund Transfer From</th>
              <td>{{ $fundTransfer->from_account->account_name }}</td>
            </tr>
            <tr>
              <th>Fund Transfer To</th>
              <td>{{ $fundTransfer->to_account->account_name }}</td>
            </tr>
            <tr>
              <th>Amount</th>
              <td>{{ $fundTransfer->amount }}</td>
            </tr>
            <tr>
              <th>Description</th>
              <td>{{ $fundTransfer->description }}</td>
            </tr>
            <tr>
              <th>Image</th>
              <td>
                <img src="{{ asset('images/fund_transfers/'.$fundTransfer->fund_transfer_image) }}" width="200" height="150">
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
<script type="text/javascript">
  $('#sampleTable').DataTable();
</script>

@endsection