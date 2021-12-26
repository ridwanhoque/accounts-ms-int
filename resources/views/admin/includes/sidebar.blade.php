<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
        <div class="app-sidebar__user">
                <div class="text-center">
                        <p class="app-sidebar__user-name">{{ auth()->user()->name }}</p>
                </div>
        </div>
        <ul class="app-menu">
                <li><a target="_blank" class="app-menu__item" href="https://docs-account-ms.herokuapp.com/"><i
                        class="app-menu__icon fa fa-file"></i><span
                        class="app-menu__label">Documentasion</span></a></li>

                        <li><a class="app-menu__item {{ Request::is('home') ? 'active':'' }}" href="{{ url('/home') }}"><i
                                class="app-menu__icon fa fa-dashboard"></i><span
                                class="app-menu__label">Dashboard</span></a></li>



                <li class="treeview {{ Formatter::checkAccountingSettingsUrl(Request::segment(1)) }}"><a
                                class="app-menu__item" href="#" data-toggle="treeview"><i
                                        class="app-menu__icon fa fa-laptop"></i><span class="app-menu__label">Account
                                        Settings</span><i class="treeview-indicator fa fa-angle-right"></i></a>
                        <ul class="treeview-menu">
                                <li><a href="{{ url('opening_assets') }}"
                                                class="treeview-item {{ Request::is('opening_assets') ? 'active':'' }}">
                                                <i class="icon fa fa-circle-o"></i>Opening Asset
                                        </a></li>
                                <li><a class="treeview-item {{ Request::is('chart_of_accounts') ? 'active':'' }}"
                                                href="{{ url('chart_of_accounts')}}"><i class="icon fa fa-circle-o"></i>
                                                Chart Of Account List </a>
                                </li>
                                <li><a class="treeview-item {{ Request::is('account-information') ? 'active':'' }}"
                                                href="{{ url('account-information')}}"><i
                                                        class="icon fa fa-circle-o"></i> Account Information
                                                List </a></li>
                                <li><a class="treeview-item {{ Request::is('payment-method') ? 'active':'' }}"
                                                href="{{ url('payment-method')}}"><i class="icon fa fa-circle-o"></i>
                                                Payment Method List </a></li>
                                <li><a class="treeview-item {{ Request::is('party') ? 'active':'' }}"
                                                href="{{ url('party')}}"><i class="icon fa fa-circle-o"></i> Supplier
                                                List </a>
                                </li>
                                <li><a class="treeview-item {{ Request::is('vendors') ? 'active':'' }}"
                                                href="{{ url('vendors')}}"><i class="icon fa fa-circle-o"></i> Buyer
                                                List </a>
                                </li>

                        </ul>
                </li>





                <li
                        class="treeview {{ Request::segment(1) == 'accounting' ? 'is-expanded':'' }}">
                        <a class="app-menu__item" href="#" data-toggle="treeview"><i
                                        class="app-menu__icon fa fa-laptop"></i><span class="app-menu__label">Account
                                        and Finance</span><i class="treeview-indicator fa fa-angle-right"></i></a>
                        <ul class="treeview-menu">
                                <li><a class="treeview-item {{ Request::is('accounting/assets/create') ? 'active':'' }}" href="{{ route('assets.create')}}"><i
                                                        class="icon fa fa-circle-o"></i> Add Asset </a>
                                </li>
                                <li><a class="treeview-item {{ Request::is('accounting/assets') ? 'active':'' }}" href="{{ route('assets.store')}}"><i
                                                        class="icon fa fa-circle-o"></i> Asset List</a></li>


                                <li>
                                        <a class="treeview-item {{ Request::is('accounting/payment_vouchers/create') ? 'active':'' }}"
                                                href="{{ route('payment_vouchers.create') }}"><i
                                                        class="icon fa fa-circle-o"></i> Add Payment Voucher </a>
                                </li>
                                <li>
                                        <a class="treeview-item {{ Request::is('accounting/payment_vouchers') ? 'active':'' }}"
                                                href="{{ route('payment_vouchers.index') }}"><i
                                                        class="icon fa fa-circle-o"></i> Payment Voucher List</a>
                                </li>
                                <li>
                                        <a class="treeview-item {{ Request::is('accounting/receive_vouchers/create') ? 'active':'' }}"
                                                href="{{ route('receive_vouchers.create') }}"><i
                                                        class="icon fa fa-circle-o"></i> Add Receive Voucher </a>
                                </li>
                                <li>
                                        <a class="treeview-item {{ Request::is('accounting/receive_vouchers') ? 'active':'' }}"
                                                href="{{ route('receive_vouchers.index') }}"><i
                                                        class="icon fa fa-circle-o"></i> Receive Voucher List</a>
                                </li>

                                <li>
                                        <a class="treeview-item {{ Request::is('accounting/journal_vouchers/create') ? 'active':'' }}"
                                                href="{{ route('journal_vouchers.create') }}"><i
                                                        class="icon fa fa-circle-o"></i> Add Journal Voucher </a>
                                </li>
                                <li>
                                        <a class="treeview-item {{ Request::is('accounting/journal_vouchers') ? 'active':'' }}"
                                                href="{{ route('journal_vouchers.index') }}"><i
                                                        class="icon fa fa-circle-o"></i> Journal Voucher List</a>
                                </li>

                                <li>
                                        <a class="treeview-item {{ Request::is('accounting/contra_vouchers/create') ? 'active':'' }}"
                                                href="{{ route('contra_vouchers.create') }}"><i
                                                        class="icon fa fa-circle-o"></i> Add Contra /
                                                Transfer </a>
                                </li>
                                <li>
                                        <a class="treeview-item {{ Request::is('accounting/contra_vouchers') ? 'active':'' }}"
                                                href="{{ route('contra_vouchers.index') }}"><i
                                                        class="icon fa fa-circle-o"></i> Contra /
                                                Transfer List</a>
                                </li>


                        </ul>
                </li>


                <!--  MIS Report starts -->
                <li class="treeview {{ Request::segment(1) == 'reports' ? 'is-expanded':'' }}"><a
                                class="app-menu__item {{ Request::segment(1) == 'reports' }}" href="#"
                                data-toggle="treeview"><i class="app-menu__icon fa fa-laptop"></i><span
                                        class="app-menu__label">MIS
                                        Report</span><i class="treeview-indicator fa fa-angle-right"></i></a>
                        <ul class="treeview-menu">
                                {{-- accounts report --}}
                                
                                <li><a class="treeview-item {{ Request::is('reports/accounts_dashboard') ? 'active':'' }}"
                                        href="{{ url('reports/accounts_dashboard')}}"><i
                                                class="icon fa fa-book"></i>
                                        Accounts Dashboard </a>
                                </li>
                                <li><a class="treeview-item {{ Request::is('reports/trial_balance') ? 'active':'' }}"
                                                href="{{ url('reports/trial_balance') }}"><i
                                                        class="icon fa fa-book"></i>
                                                Trial Balance Report </a>
                                <li><a class="treeview-item {{ Request::is('reports/ledger_balance') ? 'active':'' }}"
                                                href="{{ url('reports/ledger_balance') }}"><i
                                                        class="icon fa fa-book"></i>
                                                Ledger Balance Report </a>
                                </li>
                                <li><a class="treeview-item {{ Request::is('reports/receivable_accounts') ? 'active':'' }}"
                                                href="{{ url('reports/receivable_accounts') }}"><i
                                                        class="icon fa fa-book"></i>
                                                Accounts Receivable </a>
                                </li>

                                <li><a class="treeview-item {{ Request::is('reports/payable_accounts') ? 'active':'' }}"
                                                href="{{ url('reports/payable_accounts') }}"><i
                                                        class="icon fa fa-book"></i>
                                                Accounts Payable </a>
                                </li>
                                <li><a class="treeview-item {{ Request::is('reports/income_statement') ? 'active':'' }}"
                                                href="#"><i class="icon fa fa-book"></i>
                                                Income Statement </a>
                                </li>
                                <li><a class="treeview-item {{ Request::is('reports/balance_sheet') ? 'active':'' }}"
                                                href="{{ url('reports/balance_sheet')}}"><i class="icon fa fa-book"></i>
                                                Balance Sheet </a>
                                </li>

                                <li><a class="treeview-item {{ Request::is('reports/income_expense') ? 'active':'' }}"
                                                href="{{ url('reports/income_expense') }}"><i
                                                        class="icon fa fa-book"></i>
                                                Income Expense Report
                                        </a></li>
                                <li><a class="treeview-item {{ Request::is('reports/chart_balance') ? 'active':'' }}"
                                                href="{{ url('reports/chart_balance') }}"><i
                                                        class="icon fa fa-book"></i>
                                                Chart of Account Balance Report
                                        </a></li>


                              
               
                </li>


        <!-- MIS Report Ends  -->




        {{-- <li class="treeview {{ Request::segment(1) == 'users' ? 'is-expanded':'' }}"><a
                        class="app-menu__item {{ Request::segment(1) == 'users' }}" href="#" data-toggle="treeview"><i
                                class="app-menu__icon fa fa-laptop"></i><span class="app-menu__label">User</span><i
                                class="treeview-indicator fa fa-angle-right"></i></a>
                <ul class="treeview-menu">
                        <li><a class="treeview-item {{ Request::is('users/add-new') ? 'active':'' }}"
                                        href="{{ url('users/add-new') }}"><i class="icon fa fa-circle-o"></i> Add New
                                        User</a></li>
                        <li><a class="treeview-item {{ Request::is('users/list') ? 'active':'' }}"
                                        href="{{ url('users/list') }}"><i class="icon fa fa-circle-o"></i> User List</a>
                        </li>


                        <li><a class="treeview-item {{ Request::is('users/edit') ? 'active':'' }}"
                                        href="{{ url('users/edit') }}"><i class="icon fa fa-circle-o"></i> Edit
                                        Profile</a></li>
                        <li><a class="treeview-item {{ Request::is('users/change_password') ? 'active':'' }}"
                                        href="{{ url('users/change_password') }}"><i
                                                class="icon fa fa-circle-o"></i>Change Password</a>
                        </li>
                </ul>
        </li>



        <li class="treeview {{ Request::segment(1) == 'companies' ? 'is-expanded':'' }}"><a
                        class="app-menu__item {{ Request::segment(1) == 'companies' }}" href="#"
                        data-toggle="treeview"><i class="app-menu__icon fa fa-laptop"></i><span
                                class="app-menu__label">Company</span><i
                                class="treeview-indicator fa fa-angle-right"></i></a>
                <ul class="treeview-menu">
                        <li><a class="treeview-item {{ Request::is('companies') ? 'active':'' }}"
                                        href="{{ route('companies.index') }}"><i class="icon fa fa-circle-o"></i>
                                        Company List </a>
                        </li> --}}
                        {{-- <li><a class="treeview-item {{ Request::is('companies/create') ? 'active':'' }}"
                        href="{{ route('companies.create') }}"><i class="icon fa fa-circle-o"></i>Add
                        Company</a>
        </li>--}}
        </ul>
</aside>

@section('js')
<script>
        // let url = $(location).attr('href');
        // let appMenu = $('ul.app-menu li');
        // appMenu.each(function(){
        //   let a = $(this).children('a');
        //   if(a.attr('href') == url){
        //     a.parent('li').addClass('active');
        //   }
        // });
</script>
@endsection