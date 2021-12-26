@extends('admin.master')
@section('content')

<main class="app-content">
  <form class="form-horizontal" method="POST" action="{{ route('purchases.update', $purchase->id) }}">
    @csrf
    @method('PUT')
    <input type="hidden" name="id" value="{{ $purchase->id }}">
    <div class="app-title">
      <div>
        <h1><i class="fa fa-edit"></i> Purchase</h1>
        <p>Update Purchase Form</p>
      </div>
      <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item">Purchases</li>
        <li class="breadcrumb-item"><a href="#">Edit Purchase</a></li>
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

          <a href="{{route('purchases.index')}}" class="btn btn-primary pull-right" style="float: right;"><i
              class="fa fa-eye"></i>View Purchase</a>
          <h3 class="tile-title">Edit New Purchase</h3>

          <div class="tile-body">


            <div class="form-group row add_asterisk">
              <label class="control-label col-md-3">Date</label>
              <div class="col-md-8">
                <input name="purchase_date" value="{{ $purchase->purchase_date ?: old('purchase_date') }}"
                  class="form-control" type="text" placeholder="Date" readonly="readonly">
              </div>
            </div>

            <div class="form-group row add_asterisk">
              <label class="control-label col-md-3">Supplier</label>
              <div class="col-md-8">
                <select name="party_id" class="form-control select2">
                  @foreach ($parties as $party)
                  <option value="{{ $party->id }}" {{ $party->id==$purchase->party_id ? 'selected':'' }}>
                    {{ $party->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>


            <div class="mt-2"></div>

            <div class="form-group row add_asterisk">
              <label for="" class="control-label col-md-3">Purchase Status</label>
              <div class="col-md-8">
                <select name="status_id" class="form-control">
                  @foreach ($statuses as $status)
                  <option value="{{ $status->id }}" {{ $status->id == $purchase->status_id ? 'selected':'' }}>
                    {{ $status->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>


            <!-- add product -->


            <div class="container">
              <div class="row clearfix">
                <div class="col-md-12">
                  <table class="table" id="tab_logic">
                    <thead>
                      <tr>
                        <th class="text-center"> # </th>
                        <th width="25%" class="text-center"> Sub Raw Material Name</th>
                        <th class="text-center"> Price </th>
                        <th class="text-center"> Qty ({{ config('app.kg') }})</th>
                        <th class="text-center"> Sub Total </th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($purchase->purchase_details as $key => $details)
                      <input type="hidden" name="purchase_details_id" value="{{ $details->id }}">
                      <tr id='addr{{ $key }}'>
                        <td>{{ $key+1 }}</td>
                        <td>
                          <select name="sub_raw_material_ids[]" id="" class="form-control">
                            @foreach ($raw_materials as $raw_material)
                              @foreach ($raw_material->sub_raw_materials as $sub_raw_material)
                              <option value="{{ $sub_raw_material->id }}"
                                {{ $details->sub_raw_material_id == $sub_raw_material->id ? 'selected':'' }}>
                                   {{ $raw_material->name.' - '.$sub_raw_material->name }}</option>
                              @endforeach
                            @endforeach
                          </select>
                          <input type="hidden" name="purchase_reference" value="{{ $purchase->purchase_reference }}">
                        </td>
                        <td>
                          <input type="number" name='price[{{ $key }}]' placeholder='Enter Unit Price'
                            class="form-control price" min="0" value="{{ $details->unit_price }}" />
                        </td>
                        <td><input type="number" name='qty[{{ $key }}]' placeholder='0.00' class="form-control qty"
                            value="{{ $details->quantity }}" /></td>
                        <td><input type="number" name='total[{{ $key }}]' placeholder='0.00' class="form-control total"
                            readonly value="{{ $details->raw_material_sub_total }}" /></td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="row pull-right clearfix" style="margin-top:20px">
                <div class="col-md-12">
                  <table class="table table-bordered table-hover" id="tab_logic_total">
                    <tbody>
                      <tr>
                        <th class="text-center">Sub Total</th>
                        <td class="text-center"><input type="number" name='sub_total' placeholder='0.00'
                            class="form-control" id="sub_total" value="{{ $purchase->sub_total }}" readonly /></td>
                      </tr>
                      <tr>
                        <th class="text-center">Tax</th>
                        <td class="text-center">
                          <div class="input-group mb-2 mb-sm-0">
                            <input type="number" name="tax_percent" class="form-control" id="tax" placeholder="0"
                              min="0" max="100" value="{{ $purchase->tax_percent }}">
                            <div class="input-group-addon">%</div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="text-center">Discount Amount</th>
                        <td class="text-center"><input type="number" name='invoice_discount'
                            value="{{ $purchase->invoice_discount }}" id="invoice_discount" placeholder='0.00'
                            class="form-control" /></td>
                      </tr>
                      <tr>
                        <th class="text-center">Tax Amount</th>
                        <td class="text-center"><input type="number" name='invoice_tax'
                            value="{{ $purchase->invoice_tax }}" id="invoice_tax" placeholder='0.00'
                            class="form-control" readonly /></td>
                      </tr>
                      <tr>
                        <th class="text-center">Grand Total</th>
                        <td class="text-center"><input type="number" min="0" name='total_payable' id="total_amount"
                            placeholder='0.00' class="form-control" value="{{ $purchase->total_payable }}" readonly />
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="clearfix"></div>
            </div>





            <div class="tile-footer">
              <div class="row">
                <div class="col-md-12">
                  <button class="btn btn-primary pull-right" type="submit" type="submit"><i
                      class="fa fa-fw fa-lg fa-check-circle"></i>Edit Purchase</button>
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

<script src="{{ asset('assets/admin/js/style.js') }}"></script>

<script type="text/javascript">
  $('.select2').select2();
</script>

<script>
  $(document).ready(function(){
          var i=1;
          $("#add_row").click(function(){b=i-1;
              $('#addr'+i).html($('#addr'+b).html()).find('td:first-child').html(i+1);
              $('#tab_logic').append('<tr id="addr'+(i+1)+'"></tr>');
              i++; 
          });
          $("#delete_row").click(function(){
            if(i>1){
          $("#addr"+(i-1)).html('');
          i--;
          }
          calc();
        });
        
        $('#tab_logic tbody').on('keyup change',function(){
          calc();
        });
        $('#tax').on('keyup change',function(){
          calc_total();
        });
      
        $('#invoice_discount').on('keyup change', function(){
          calc_total();
        });
        
      
      });
      
      function calc()
      {
        $('#tab_logic tbody tr').each(function(i, element) {
          var html = $(this).html();
          if(html!='')
          {
            var qty = $(this).find('.qty').val();
            var price = $(this).find('.price').val();
            $(this).find('.total').val(qty*price);
            
            calc_total();
          }
          });
      }
      
      function calc_total()
      {
        total=0;
        $('.total').each(function() {
              total += parseInt($(this).val());
          });
        $('#sub_total').val(total.toFixed(2));
        tax_sum=total/100*$('#tax').val();
        $('#invoice_tax').val(tax_sum.toFixed(2));
        var discount = $('#invoice_discount').val();
        $('#total_amount').val(((tax_sum+total)-discount).toFixed(2));
      }
</script>


@include('admin.includes.date_field')
@endsection