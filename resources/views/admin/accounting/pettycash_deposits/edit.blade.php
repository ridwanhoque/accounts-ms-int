@extends('admin.master')
@section('content')

<main class="app-content">
  <form class="form-horizontal" method="POST" action="{{ route('pettycash_deposits.update', $pettycashDeposit->id) }}"> 
  @csrf
@method('PUT')
<input type="hidden" name="id" value="{{ $pettycashDeposit->id }}">
  <div class="app-title">
    <div>
      <h1><i class="fa fa-edit"></i> Pettycash Deposit Information</h1>
      <p>Update Pettycash Deposit Form</p>
    </div>
    <ul class="app-breadcrumb breadcrumb">
      <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
      <li class="breadcrumb-item">Pettycash Deposits</li>
      <li class="breadcrumb-item"><a href="#">Edit Pettycash Deposit</a></li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      @if(Session::get('error_message'))
      <div class="alert alert-danger">
        {{ Seession::get('error_message') }}
      </div>
      @endif
      @if($errors->any())
      <div class="alert-danger">
        <ul class="list-unstyled">
          @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
      @endif

      <div class="tile">

        <a href="{{route('pettycash_deposits.index')}}" class="btn btn-primary pull-right" style="float: right;"><i
            class="fa fa-eye"></i>View Pettycash Deposit</a>
        <h3 class="tile-title">Edit New Pettycash Deposit</h3>

        <div class="tile-body">

          <div class="form-group row add_asterisk">
            <label class="control-label col-md-3">Date</label>
            <div class="col-md-8">
                <input type="text" class="form-control" name="pettycash_deposit_date" value="{{ $pettycashDeposit->pettycash_deposit_date }}" readonly="readonly">
            </div>
          </div>

          <div class="form-group row add_asterisk">
            <label class="control-label col-md-3">Received By</label>
            <div class="col-md-8">
              <select name="received_by" class="form-control select2">
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ $user->id == $pettycashDeposit->received_by }}>{{ $user->name }}</option>
                @endforeach
              </select>
              <div class="text-danger">{{ $errors->has('received_by') ? $errors->first('received_by'):'' }}</div>
            </div>
          </div>

          <div class="form-group row add_asterisk">
            <label class="control-label col-md-3">Deposit From Account</label>
            <div class="col-md-8">
              <select name="account_information_id" class="form-control select2">
                @foreach ($accounts as $account)
                    <option value="{{ $account->id }}" {{ $account->id == $pettycashDeposit->account_information_id }}>{{ $account->account_name }}</option>
                @endforeach
              </select>
              <div class="text-danger">{{ $errors->has('account_information_id') ? $errors->first('account_information_id'):'' }}</div>
            </div>
          </div>

          <div class="form-group row add_asterisk">
            <label class="control-label col-md-3">Amount</label>
            <div class="col-md-8">
              <input name="amount" value="{{ $pettycashDeposit->amount }}" class="form-control" type="number" placeholder="Amount">
              <div class="text-danger">{{ $errors->has('amount') ? $errors->first('amount'):'' }}</div>
            </div>
          </div>
          
        </div>



        <div class="tile-footer">
          <div class="row">
            <div class="col-md-12">
              <button class="btn btn-primary pull-right" type="submit"><i
                  class="fa fa-fw fa-lg fa-check-circle"></i>Edit Pettycash Deposit</button>
            </div>
          </div>
        </div>



      </div>


    </div>

  </div>
  </div>
  </div>

  </form>
</main>

<script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/plugins/select2.min.js')}}"></script>

<script>
$('.select2').select2();
</script>

@endsection