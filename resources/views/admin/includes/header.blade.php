<header class="app-header">
  <a class="app-header__logo" href="{{ url('/') }}">{{ config('app.project_title') }}</a>
      <!-- Sidebar toggle button-->
  <a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar"></a>
  <h4 class="text-white" style="margin-top: 11px;">
    @php
    $company_id = Auth::user()->company_id;
    $company = \App\Company::where('id',$company_id)->first();
    @endphp
    {{ $company->name }}
  </h4>

      <!-- Navbar Right Menu-->
      <ul class="app-nav">
        <!--Notification Menu-->
        <!-- User Menu-->
        <li class="dropdown"><a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Open Profile Menu"><i class="fa fa-user fa-lg"></i></a>
          <ul class="dropdown-menu settings-menu dropdown-menu-right">
            <li><a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit()"><i class="fa fa-sign-out fa-lg"></i> Logout</a></li>
            <form action="{{ route('logout') }}" id="logout-form" style="display: none;" method="POST">
              @csrf
            </form>
          </ul>
        </li>
      </ul>
    </header>