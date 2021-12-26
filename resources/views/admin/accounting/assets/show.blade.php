@extends('admin.master')
@section('content')
<main class="app-content">
        <div class="app-title">
          <div>
            <h1><i class="fa fa-edit"></i> Show Asset</h1>
            <p>Show Asset</p>
          </div>
          <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item">Assets</li>
            <li class="breadcrumb-item"><a href="#">Show Asset</a></li>
          </ul>
        </div>
        <div class="row">
        
          <div class="col-md-12">
          <div class="tile">
            <h3 class="tile-title">Assets</h3>
            <table class="table table-bordered">
              
              <tbody>
                <tr>
                  <th>Date</th>
                  <td>{{ $asset->asset_date }}</td>
                </tr>
                <tr>
                  <th>Party</th>
                  <td>{{ $asset->party->name }}</td>
                </tr>
                <tr>
                  <td colspan="2">
                    <table class="table no-spacing">
                        <thead>
                          <tr>
                            <th>Asset Head</th>
                            <th>Amount</th>
                            <th>Validity</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($asset->asset_details as $details)
                          <tr>
                            <td>{{ $details->chart_of_account->head_name ?? '' }}</td>
                            <td>{{ $details->amount }}</td>
                            <td>{{ $details->years.' '.config('app.yrs') }}</td>
                          </tr>
                          @endforeach
                          <tr>
                            <th class="text-right">Total</th>
                            <th colspan="2" style="padding-left:2px;">{{ $asset->total_amount }}</th>
                          </tr>
                        </tbody>
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
<script type="text/javascript">$('#sampleTable').DataTable();</script>

        @endsection