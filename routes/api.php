<?php

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

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');


Route::post('/chart-of-accounts','ChartOfAccountsController@saveAccount');
Route::get('/customers/{customer?}/collaterals','CustomersController@collaterals');

Route::get('/chart-of-accounts/{account_code?}/account','ChartOfAccountsController@getAccountFromAccountCode');
Route::get('/chart-of-accounts/{account_code?}/account-code','ChartOfAccountsController@getLoanFromAccountCode');
Route::get('/chart-of-accounts','ChartOfAccountsController@getChartOfAccounts');

Route::get('/loans/{loan}/other-additions', 'LoansController@otherAdditions');
Route::get('/loans/{loan}/other-deductions', 'LoansController@otherDeductions');
Route::get('/loans/{loan}', 'LoansController@getLoan');

Route::delete('/collections/{collection}/less-account/{aaccount}/delete', 'CollectionsController@deleteLessAccount');
Route::delete('/collections/{collection}/additional-account/{aaccount}/delete', 'CollectionsController@deleteAdditionalAccount');
Route::get('/collections/{collection}/less-accounts', 'CollectionsController@lessAccounts');
Route::get('/collections/{collection}/additional-accounts', 'CollectionsController@additionalAccounts');
Route::post('/collections/{loan}/loan-computation', 'CollectionsController@getLoanComputation');
Route::get('/collections/{collection}', 'CollectionsController@getCollection');

Route::get('/check-voucher/{check_voucher}/check-voucher-details', 'CheckVoucherController@checkVoucherDetails');
Route::delete('/check-voucher-details/{check_voucher_detail}/delete', 'CheckVoucherController@deleteDetail');


Route::get('/general-ledgers/{general_ledger}/general-ledger-details', 'GeneralLedgersController@generalLedgerDetails');
Route::delete('/general-ledgers/{general_ledger}/account/{account}/delete', 'GeneralLedgersController@deleteDetail');

