<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

use App\Http\Controllers\AccountInformationController;
use App\Http\Controllers\Accounts\ContraVoucherController;
use App\Http\Controllers\Accounts\JournalController;
use App\Http\Controllers\Accounts\JournalVoucherController;
use App\Http\Controllers\Accounts\PaymentVoucherController;
use App\Http\Controllers\Accounts\ReceiveVoucherController;
use App\Http\Controllers\AssetChartController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\ChartOfAccountController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DirectProductionController;
use App\Http\Controllers\FmKutchaController;
use App\Http\Controllers\FundTransferController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IssueMaterialController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\OpeningAssetController;
use App\Http\Controllers\OpeningFmKutchaController;
use App\Http\Controllers\OpeningHaddiPowderController;
use App\Http\Controllers\OpeningProductController;
use App\Http\Controllers\OpeningRawMaterialStockController;
use App\Http\Controllers\OpeningSheetController;
use App\Http\Controllers\OpeningSubRawMaterialController;
use App\Http\Controllers\PartyController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PettycashChartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductDeliveryController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchaseReceiveController;
use App\Http\Controllers\RawMaterialController;
use App\Http\Controllers\RawMaterialStockController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SaleQuotationController;
use App\Http\Controllers\SheetProductionController;
use App\Http\Controllers\SheetSizeController;
use App\Http\Controllers\SubRawMaterialController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TemporaryDirectProductionController;
use App\Http\Controllers\TemporarySheetProductionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\WastageController;
use App\Http\Controllers\AccountsDashboardController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\Accounts\TrialBalanceController;
use App\Http\Controllers\Accounts\LedgerBalanceReportController;
use App\Http\Controllers\Accounts\ReceivableAccountsController;
use App\Http\Controllers\Accounts\PayableAccountsController;
use App\Http\Controllers\Accounts\BalanceSheetController;
use App\Http\Controllers\Accounts\IncomeExpenseController;
use App\Http\Controllers\Accounts\ChartBalanceReportController;
use App\Http\Controllers\RawMaterialStockBatchController;
use App\Http\Controllers\SheetproductiondetailsStockController;
use App\Http\Controllers\KutchaWastageStockController;
use App\Http\Controllers\KutchaSummaryReportController;
use App\Http\Controllers\HaddiPowderStockController;
use App\Http\Controllers\ProductSummaryReportController;
use App\Http\Controllers\ProductStockController;
use App\Http\Controllers\ProductBranchStockController;
use App\Http\Controllers\TemporaryDailyProductionController;
use App\Http\Controllers\DailyProductionController;
use App\Http\Controllers\ProductStockTransferController;
use App\Http\Controllers\PettycashController;
use App\Http\Controllers\PettycashDepositController;
use App\Http\Controllers\PettycashExpenseController;
use App\Http\Controllers\IncomeStatementController;
// use App\Http\Controllers\ProductBranchStockController;



Route::get('/', function () {
    return view('auth/login');
});

Auth::routes();

Route::group(['middleware' => 'auth'], function () {

    Route::resource('companies', CompanyController::class);

    Route::get('users/edit', [UserController::class, 'edit']);
    Route::post('users/update', [UserController::class, 'update']);
    Route::get('users/change_password', [UserController::class, 'change_password_form']);
    Route::post('users/change_password', [UserController::class, 'change_password']);
    Route::get('users/list', [UserController::class, 'user_list']);

    Route::get('users/add-new', [UserController::class, 'add_new_user_form']);
    Route::post('users/add-new', [UserController::class, 'add_new_user']);
    Route::get('users/edit-user/{id}', [UserController::class, 'edit_user_form']);
    Route::post('users/update-user', [UserController::class, 'edit_user']);
    Route::get('users/show/{id}', [UserController::class, 'show_user']);
    Route::post('users/user-delete', [UserController::class, 'delete_user']);

    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::resource('suppliers', SupplierController::class);
    Route::resource('customers', CustomerController::class);

//    Accounting and finance
    Route::resource('chart_of_accounts', ChartOfAccountController::class);
    Route::resource('pettycash_charts', PettycashChartController::class);
    Route::resource('asset_charts', AssetChartController::class);
    Route::get('opening_assets', [OpeningAssetController::class, 'index']);
    Route::post('opening_asset_store', [OpeningAssetController::class, 'store']);

    // Role permissions
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('user_roles', UserRoleController::class);

    Route::group(['prefix' => 'accounting'], function () {
        Route::get('fund_transfers/account_balance', [FundTransferController::class, 'get_account_balance']);
        Route::get('payment_invoice/{id}', [DuePaymentController::class, 'payment_invoice']);
        Route::resource('due_payments', DuePaymentController::class);

        //accounts
        Route::resource('payment_vouchers', PaymentVoucherController::class);
        Route::resource('receive_vouchers', ReceiveVoucherController::class);
        Route::resource('journal_vouchers', JournalVoucherController::class);
		Route::resource('contra_vouchers', ContraVoucherController::class);
        Route::resource('journals', JournalController::class);

    });

    Route::group(['prefix' => 'reports'], function () {

        //Accounting Report
        Route::get('income-expense', [ReportController::class, 'reportPage']);
        Route::get('income-expense/filter', [ReportController::class, 'searchReport']);
        Route::get('accounts_dashboard', [AccountsDashboardController::class, 'report']);
        Route::get('accounts_receivable', [AccountsReceivableController::class, 'report']);
        Route::get('accounts_payable', [AccountsPayableController::class, 'report']);
        Route::get('income_statement', [IncomeStatementController::class, 'report']);
        Route::get('cash_flow', [CashFlowController::class, 'report']);
        Route::get('ledger_balance', [LedgerBalanceReportController::class, 'report']);
        Route::get('trial_balance', [TrialBalanceController::class, 'report']);
        Route::get('trial_balance/{id}', [TrialBalanceController::class, 'details']);
        //new
        Route::get('chart_balance/{id?}', [ChartBalanceReportController::class, 'index']);
        Route::get('chart_tree/{id?}', [ChartTreeController::class, 'index']);
    });


    Route::get('test', [SheetProductionController::class, 'test']);



});
