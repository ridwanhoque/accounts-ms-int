@extends('admin.master')
@section('content')

<main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-th-list"></i> Voucher Payment Information</h1>
          <p>Voucher Payment information </p>
        </div>
        <ul class="app-breadcrumb breadcrumb side">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item">Voucher Payment Information</li>
          <li class="breadcrumb-item active"><a href="#">Voucher Payment Information Table</a></li>
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
            <h3 class="tile-title">Voucher Payment List </h3>
            <div class="tile-body">
              <table class="table table-hover table-bordered" id="">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th>Account</th>
                    <th>Payment Method</th>
                    <th>Amount</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($voucher_payments as $voucher_payment)
                  <tr>
                        <td>{{ $voucher_payment->voucher->voucher_date }}</td>
                        <td>{{ $voucher_payment->account_information->account_name }}</td>
                        <td>{{ $voucher_payment->payment_method->method_name }}</td>
                        <td>{{ $voucher_payment->paid_amount }}</td>
                        <td>

                            <a class="btn btn-primary btn-sm" title="View" href="{{ url('accounting/payment_invoice', $voucher_payment->id) }}"> <i class="fa fa-eye"></i> </a> 
                            <a class="btn btn-danger btn-sm" title="Delete" onclick="formSubmit('{{$voucher_payment->id}}')" href="#"> <i class="fa fa-trash"></i> </a>
                                
                            <form action="{{ route('due_payments.destroy', $voucher_payment->id) }}" id="deleteForm_{{$voucher_payment->id}}" method="POST">
                                @csrf
                                @method("DELETE")
                                <input type="hidden" name="voucher_payment_id" value="{{ $voucher_payment->id }}">
                            </form>
                            
                        </td>
                    </tr>                        
                  @endforeach
                  
                </tbody>
              </table>
              {{ $voucher_payments->links() }}
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