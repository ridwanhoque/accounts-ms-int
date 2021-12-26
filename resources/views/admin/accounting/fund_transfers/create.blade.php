@extends('admin.master')
@section('content')

<main class="app-content">
  <form class="form-horizontal" method="POST" action="{{ route('fund_transfers.store') }}" enctype="multipart/form-data"> 
  @csrf
    <input type="hidden" name="account_balance" id="account_balance" value="{{ old('account_balance') ?: '0' }}">
  <div class="app-title">
    <div>
      <h1><i class="fa fa-edit"></i> Fund Transfer Information</h1>
      <p>Create Fund Transfer Form</p>
    </div>
    <ul class="app-breadcrumb breadcrumb">
      <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
      <li class="breadcrumb-item">Fund Transfers</li>
      <li class="breadcrumb-item"><a href="#">Add Fund Transfer</a></li>
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

        <a href="{{  route('fund_transfers.index') }}" class="btn btn-primary pull-right" style="float: right;"><i
            class="fa fa-eye"></i>View Fund Transfer</a>
        <h3 class="tile-title">Add New Fund Transfer</h3>

        <div class="tile-body">

          

          <div class="form-group row add_asterisk">
            <label class="control-label col-md-3">Date</label>
            <div class="col-md-8">
                <input type="text" name="fund_transfer_date" class="form-control" value="{{ date('Y-m-d') }}" readonly="readonly">
            </div>
          </div>

          <div class="form-group row add_asterisk">
            <label class="control-label col-md-3">From Account</label>
            <div class="col-md-8">
              <select name="from_account_id" id="from_account_id" class="form-control select2">
                <option value="0">select</option>
                @foreach ($account_information as $account)
                    <option value="{{ $account->id }}" {{ $account->id == old('from_account_id') ? 'selected':'' }}>{{ $account->account_name }}</option>
                @endforeach
              </select>
              <div class="text-danger">{{ $errors->has('from_account_id') ? $errors->first('from_account_id'):'' }}</div>
            </div>
          </div>

          <div class="form-group row add_asterisk">
            <label class="control-label col-md-3">To Account</label>
            <div class="col-md-8">
              <select name="to_account_id" class="form-control select2">
                <option value="0">select</option>
                @foreach ($account_information as $account)
                    <option value="{{ $account->id }}" {{ $account->id == old('to_account_id') ? 'selected':'' }}>{{ $account->account_name }}</option>
                @endforeach
              </select>
              <div class="text-danger">{{ $errors->has('to_account_id') ? $errors->first('to_account_id'):'' }}</div>
            </div>
          </div>

          <div class="form-group row add_asterisk">
            <label class="control-label col-md-3">Amount</label>
            <div class="col-md-8">
              <input name="amount" value="{{ old('amount') }}" class="form-control" type="number" placeholder="Amount">
              <div class="text-danger">{{ $errors->has('amount') ? $errors->first('amount'):'' }}</div>
            </div>
          </div>
          
          <div class="form-group row add_asterisk">
            <label class="control-label col-md-3">Image</label>
            <div class="col-md-8">
              <input name="fund_transfer_image" id="fund_transfer_image" value="{{ old('fund_transfer_image') }}" type="file" placeholder="Image">
              <div class="text-danger">{{ $errors->has('fund_transfer_image') ? $errors->first('fund_transfer_image'):'' }}</div>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-md-3"></div>
            <div class="col-md-8">
                <img id="fundTransferImage" src="#" alt="your image" width="200px" height="150px" />
            </div>
          </div>
          

          <div class="form-group row add_asterisk">
            <label class="control-label col-md-3">Description</label>
            <div class="col-md-8">
              <textarea name="description" id="" cols="30" rows="10" class="form-control" placeholder="Fund Transfer Description">{{ old('description') }}</textarea>
              <div class="text-danger">{{ $errors->has('description') ? $errors->first('description'):'' }}</div>
            </div>
          </div>



        </div>



        <div class="tile-footer">
          <div class="row">
            <div class="col-md-12">
              <button class="btn btn-primary pull-right" type="submit" type="submit"><i
                  class="fa fa-fw fa-lg fa-check-circle"></i>Add Fund Transfer</button>
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

<script>
  function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    
    reader.onload = function(e) {
      $('#fundTransferImage').attr('src', e.target.result);
    }
    
    reader.readAsDataURL(input.files[0]);
  }
}

$("#fund_transfer_image").change(function() {
  readURL(this);
});

$('#from_account_id').change(function(){
  var account_id = $(this).val();
  if( account_id > 0){
    $.ajax({
      url: '{{ url("accounting/fund_transfers/account_balance") }}',
      type: 'GET',
      data: 'id='+ account_id,
      success:function(res){
        $('#account_balance').val(res);
      }
    });
  }else{
    $('#account_balance').val('0');
  }
});
</script>

@endsection