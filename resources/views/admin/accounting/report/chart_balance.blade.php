@extends('admin.master')
@section('title','Chart Of Accounts')

@section('content')

<style>

    /* Remove default bullets */
ul, #myUL {
  list-style-type: none;
}

/* Remove margins and padding from the parent ul */
#myUL {
  margin: 0;
  padding: 0;
}

/* Style the caret/arrow */
.caret {
  cursor: pointer;
  user-select: none; /* Prevent text selection */
}

/* Create the caret/arrow with a unicode, and style it */
.caret::before {
  content: "\25B6";
  color: black;
  display: inline-block;
  margin-right: 6px;
}

/* Rotate the caret/arrow icon when clicked on (using JavaScript) */
.caret-down::before {
  transform: rotate(90deg);
}

/* Hide the nested list */
.nested {
  display: none;
}

/* Show the nested list when the user clicks on the caret/arrow (with JavaScript) */
.active {
  display: block;
}


</style>
<main class="app-content">

    <div class="app-title">
        <div>
            <h1><i class="fa fa-edit"></i> @yield('title')</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">@yield('title')</a></li>
        </ul>
    </div>



    @if ($errors->any())
    <div class="alert alert-dismissible alert-danger">
        <button class="close" type="button" data-dismiss="alert">×</button>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if (Session::get('error'))
    <div class="alert alert-dismissible alert-danger">
        <button class="close" type="button" data-dismiss="alert">×</button>
        <strong>Error !</strong> {{ Session::get('error') }}
    </div>
    @endif


    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-title"> @yield('title') </div>

                <div class="tile-body">


                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th width="60%">Particular</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($chart_of_accounts as $key => $chart)
                            <tr>
                                <td>{{ $chart->account_code }}</td>
                                <td>
                                    <a href="{{ request()->root().'/reports/chart_balance/'.$chart->id }}">
                                        {{ $chart->head_name }}
                                    </a>
                                </td>
                                <td>{{ Formatter::addComma($chart->balance) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $chart_of_accounts->links() }}


                    {{-- <ul id="myUL">
                        <li><span class="caret">Beverages</span>
                          <ul class="nested">
                            <li>Water</li>
                            <li>Coffee</li>
                            <li><span class="caret">Tea</span>
                              <ul class="nested">
                                <li>Black Tea</li>
                                <li>White Tea</li>
                                <li><span class="caret">Green Tea</span>
                                  <ul class="nested">
                                    <li>Sencha</li>
                                    <li>Gyokuro</li>
                                    <li>Matcha</li>
                                    <li>Pi Lo Chun</li>
                                  </ul>
                                </li>
                              </ul>
                            </li>
                          </ul>
                        </li>
                      </ul> --}}

                </div>

            </div>


        </div>


    </div>

</main>


@endsection


@section('js')
<script type="text/javascript" src="{{ asset('assets/admin/js/plugins/bootstrap-datepicker.min.js') }}"></script>
<script>
    $('.date').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            todayHighlight: true
        });
</script>
<script>
    var toggler = document.getElementsByClassName("caret");
var i;

for (i = 0; i < toggler.length; i++) {
  toggler[i].addEventListener("click", function() {
    this.parentElement.querySelector(".nested").classList.toggle("active");
    this.classList.toggle("caret-down");
  });
}
</script>
@stop