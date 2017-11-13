<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

use App\Collateral;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index');
Route::resource('/customers','CustomersController');
Route::resource('/banks','BanksController');
Route::resource('/chart-of-accounts','ChartOfAccountsController');
Route::resource('/collateral-classes','CollateralClassesController');
Route::resource('/collaterals','CollateralsController');
Route::resource('/collaterals-rem','CollateralsREMController');
Route::resource('/loans','LoansController');
Route::resource('/computation-slips','ComputationSlipsController');
Route::resource('/check-vouchers','CheckVoucherController');
Route::resource('/general-ledgers','GeneralLedgersController');
Route::resource('/collections','CollectionsController');
Route::resource('/settings','SettingsController');

Route::get('availment-report','ReportsController@availmentReport');
Route::post('availment-report','ReportsController@generateAvailmentReport');
Route::get('print-availment-report','ReportsController@printAvailmentReport');

Route::get('collection-report','ReportsController@collectionReport');
Route::post('collection-report','ReportsController@generateCollectionReport');
Route::get('print-collection-report','ReportsController@printCollectionReport');

Route::get('daily-collection-report','ReportsController@dailyCollectionReport');
Route::post('daily-collection-report','ReportsController@generateDailyCollectionReport');
Route::get('print-daily-collection-report','ReportsController@printDailyCollectionReport');

Route::get('cash-position-report','ReportsController@cashPositionReport');
Route::post('cash-position-report','ReportsController@generateCashPositionReport');
Route::get('print-cash-position-report','ReportsController@printCashPositionReport');

Route::get('trial-balance','ReportsController@trialBalance');
Route::post('trial-balance','ReportsController@generateTrialBalance');
Route::get('print-trial-balance','ReportsController@printTrialBalance');

Route::get('general-ledger-activity-report','ReportsController@generalLedgerActivityReport');
Route::post('general-ledger-activity-report','ReportsController@generateGeneralLedgerActivityReport');
Route::get('print-general-ledger-activity-report','ReportsController@printGeneralLedgerActivityReport');

Route::get('income-statement','ReportsController@incomeStatement');
Route::post('income-statement','ReportsController@generateIncomeStatement');
Route::get('print-income-statement','ReportsController@printIncomeStatement');

Route::get('balance-sheet','ReportsController@balanceSheet');
Route::post('balance-sheet','ReportsController@generateBalanceSheet');
Route::get('print-balance-sheet','ReportsController@printBalanceSheet');

Route::get('aging-of-accounts-receivables','ReportsController@agingOfAccountsReceivables');
Route::post('aging-of-accounts-receivables','ReportsController@generateAgingOfAccountsReceivables');
Route::get('print-aging-of-accounts-receivables','ReportsController@printAgingOfAccountsReceivables');

Route::get('journal-listings','ReportsController@journalListings');
Route::post('journal-listings','ReportsController@generateJournalListings');
Route::get('print-journal-listings','ReportsController@printJournalListings');

Route::get('/computation-slips/{loan}/print-computation-slip','ComputationSlipsController@printComputationSlip');
Route::get('/computation-slips/{loan}/print-cash-voucher','ComputationSlipsController@printCashVoucher');
Route::get('/loans/{loan}/print-processing-slip','LoansController@printProcessingSlip');
Route::get('/loans/{loan}/print-account-ledger','LoansController@printAccountLedger');
Route::get('/collaterals/{collateral}/print-appraisal-report','CollateralsController@printAppraisalReport');
Route::get('/collaterals/{collateral}/print-appraisal-rem-report','CollateralsController@printAppraisalREMReport');
Route::get('/loans/{loan}/print-credit-investigation-report','LoansController@printCreditInvestigationReport');
Route::get('/loans/{loan}/print-chattel-mortgage','LoansController@printChattelMortgage');
Route::get('/loans/{loan}/print-disclosure-statement','LoansController@printDisclosureStatement');
Route::get('/loans/{loan}/print-promissory-note','LoansController@printPromissoryNote');

Route::get('/check-vouchers/{check_voucher}/print-cash-voucher','CheckVoucherController@printCashVoucher');