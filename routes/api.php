<?php

use App\Http\Controllers\ChartOfAccountController;
use App\Http\Controllers\OpeningSheetController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\ProductStockController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SheetproductiondetailsStockController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


// Route::group(['middleware' => ['auth', 'api']], function()
// {
//     Route::get('sale/invoice_quantity', 'SaleController@ajax_invoice_quantity');
// }
// );

Route::middleware('api')->group(function () {
    Route::get('sale/invoice_quantity', [SaleController::class, 'ajax_invoice_quantity']);
    Route::get('sheetproductiondetailsstock/sheet_kg_roll', [SheetproductiondetailsStockController::class, 'ajax_sheet_kg_roll']);
    Route::get('product_stocks/get_product_stock', [ProductStockController::class, 'ajax_get_product_stock']);
    Route::get('product_stocks/get_product_branch_stock', [ProductStockController::class, 'ajax_get_product_branch_stock']);
    Route::get('payment_methods/get_payment_method', [PaymentMethodController::class, 'ajax_get_payment_method']);
    Route::post('opening_sheet_save/qty_kgs', [OpeningSheetController::class, 'ajax_qty_save']);
    Route::get('chart_of_accounts/get_charts', [ChartOfAccountController::class, 'ajax_get_charts']);
    Route::get('chart_of_accounts/get_chart_data', [ChartOfAccountController::class, 'ajax_get_chart_of_account_balance']);
});