@extends('admin.master')
@section('content')
<main class="app-content">
  <div class="app-title">
    <div>
      <h1><i class="fa fa-edit"></i> Manage Pettycash Expense</h1>
      <p>Manage Pettycash Expense Form</p>
    </div>
    <ul class="app-breadcrumb breadcrumb">
      <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
      <li class="breadcrumb-item">Pettycash Expenses</li>
      <li class="breadcrumb-item"><a href="#">Show Pettycash Expense</a></li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="tile">
        <h3 class="tile-title">Pettycash Expenses Table</h3>
        <table class="table table-bordered">

          <tbody>
            <tr>
              <th>Date</th>
              <td>{{ $pettycashExpense->pettycash_expense_date }}</td>
            </tr>
            <tr>
              <th>Total Amount</th>
              <td>{{ $pettycashExpense->total_amount }}</td>
            </tr>
            <tr>
              <th>Details</th>
              <td>
                <table>
                  <tr>
                    <th>Pettycash Head</th>
                    <th>Purpose</th>
                    <th>Amount</th>
                  </tr>
                  @foreach ($pettycashExpense->pettycash_expense_details as $details)
                    <tr>
                      <td>{{ $details->pettycash_chart->name }}</td>
                      <td>{{ $details->purpose }}</td>
                      <td>{{ $details->amount }}</td>
                    </tr>
                  @endforeach
                </table>
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