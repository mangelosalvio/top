<?php

namespace App\Http\Controllers;

use App\Bank;
use App\ChartOfAccount;
use App\CheckVoucher;
use App\CheckVoucherDetail;
use App\Customer;
use App\GeneralLedger;
use App\Journal;
use App\Library\NumToWords;
use App\Loan;
use App\Setting;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use stdClass;

class CheckVoucherController extends Controller
{
    public $arr_rules;
    public $route;

    public function __construct(){
        $route = "check-vouchers";
        $this->route = $route;

        $this->arr_rules  = [

        ];

        $chart_of_accounts = ChartOfAccount::all()->pluck('account_desc','id');
        $bank_accounts = Bank::all()->pluck('bank_desc','id');
        $expense_accounts = ChartOfAccount::type('E')->pluck('account_desc','id');
        $customers = Customer::all()->pluck('name','id');

        $search_data = [ 'search_url' => $this->route ];

        return view()->share(compact([
            'search_data','customers','collateral_classes','trans_types','collaterals','route',
            'chart_of_accounts',
            'bank_accounts',
            'expense_accounts'
        ]));
    }

    public function index(Request $request){
        $keyword = $request->input('keyword');

        if ( $keyword ) {
            $check_vouchers = CheckVoucher::where('id','like',"%$keyword%")
                ->orWhere('customer_name','like',"%$keyword%")
                ->paginate();
        } else {
            $check_vouchers = CheckVoucher::paginate();
        }

        return view(str_replace("-","_",$this->route) . ".search_" . str_replace("-","_",$this->route),compact([
            'check_vouchers'
        ]));
    }

    public function create(){
        return view(str_replace("-","_",$this->route).".".str_replace("-","_",$this->route),compact([
        ]));
    }

    public function store(Request $request){
        //dd($request->all());
        $this->validate($request,$this->arr_rules);
        $CheckVoucher = CheckVoucher::create($request->all());

        if ( $request->has('check_voucher_details') ) {
            foreach ( $request->input('check_voucher_details')['chart_of_account_id']  as $i => $chart_of_account_id) {
                $CheckVoucher->details()->save(new CheckVoucherDetail([
                    'chart_of_account_id' => $chart_of_account_id,
                    'debit' => $request->input('check_voucher_details')['debit'][$i],
                    'credit' => $request->input('check_voucher_details')['credit'][$i]
                ]));
            }
        }

        if (!$this->post($CheckVoucher)) {
            return Redirect::to("/{$this->route}/{$CheckVoucher->id}/edit")->with('flash_message','Unable to post to ledger. Please check accounts.');
        }

        return Redirect::to("/{$this->route}/{$CheckVoucher->id}/edit")->with('flash_message','Information Saved');
    }

    public function edit(CheckVoucher $check_voucher){
        return view(str_replace("-","_",$this->route).".".str_replace("-","_",$this->route),compact([
            'check_voucher'
        ]));

    }

    public function update(Request $request, CheckVoucher $check_voucher){
        $this->validate($request,$this->arr_rules);
        $check_voucher->update($request->all());

        if ( $request->has('check_voucher_details') ) {
            foreach ( $request->input('check_voucher_details')['chart_of_account_id']  as $i => $chart_of_account_id) {

                if ( empty($request->input('check_voucher_details')['id'][$i]) ) {
                    $check_voucher->details()->save(new CheckVoucherDetail([
                        'chart_of_account_id' => $chart_of_account_id,
                        'debit' => $request->input('check_voucher_details')['debit'][$i],
                        'credit' => $request->input('check_voucher_details')['credit'][$i]
                    ]));
                } else {
                    CheckVoucherDetail::find($request->input('check_voucher_details')['id'][$i])
                        ->update([
                            'chart_of_account_id' => $chart_of_account_id,
                            'debit' => $request->input('check_voucher_details')['debit'][$i],
                            'credit' => $request->input('check_voucher_details')['credit'][$i]
                        ]);
                }

            }
        }

        if (!$this->post($check_voucher)) {
            return Redirect::to("/{$this->route}/{$check_voucher->id}/edit")->with('flash_message','Unable to post to ledger. Please check accounts.');
        }


        return Redirect::to("/{$this->route}/{$check_voucher->id}/edit")->with('flash_message','Information Saved');
    }

    public function destroy(CheckVoucher $check_voucher){
        $GL = $check_voucher->gl();

        if ( $GL ) {
            $GL->delete();
        }
        $check_voucher->delete();
        return Redirect::to("/{$this->route}")->with('flash_message','Check Voucher Deleted');
    }


    public function printCashVoucher(CheckVoucher $check_voucher){

        $formatter = new NumToWords($check_voucher->amount);
        $words = $formatter->getWord();


        return view('reports.print_cash_voucher_2', compact([
            'check_voucher',
            'words'
        ]));
    }

    public function checkVoucherDetails(CheckVoucher $check_voucher){
        return $check_voucher->details()->with('account')->get();
    }

    public function deleteDetail(CheckVoucherDetail $check_voucher_detail) {
        $CheckVoucher = $check_voucher_detail->checkVoucher;
        $check_voucher_detail->delete();

        /**
         * update amount when deleting
         */
        $total_amount = $CheckVoucher->details->sum(function($Detail){
            return $Detail->debit - $Detail->credit;
        });

        $CheckVoucher->amount = $total_amount;
        $CheckVoucher->save();
    }

    private function post(CheckVoucher $check_voucher){

        //dd($check_voucher->bank);

        $arr_accounts = [];

        if ( $check_voucher->amount ) {
            $arr_accounts[$check_voucher->bank->account->id] = [
                'credit' => $check_voucher->amount,
                'debit' => 0,
                'description' => NULL
            ];
        }

        foreach ( $check_voucher->details as $i => $Detail ) {

            if ( isset( $arr_accounts[$Detail->chart_of_account_id] ) ) {
                $arr_accounts[$Detail->chart_of_account_id]['debit'] += $Detail->debit;
                $arr_accounts[$Detail->chart_of_account_id]['credit'] += $Detail->credit;
            } else {
                $arr_accounts[$Detail->chart_of_account_id] = [
                    'credit' => $Detail->credit,
                    'debit' => $Detail->debit,
                    'description' => NULL
                ];
            }
        }

        $Total = new stdClass();
        $Total->debit = $Total->credit = 0;

        foreach ( $arr_accounts as $account => $arr ) {
            $Total->debit += $arr['debit'];
            $Total->credit += $arr['credit'];
        }


        if ( $this->hasEmptyAccounts($arr_accounts) ) {
            return false;
        } else if ( $Total->debit != $Total->credit ) {
            Session::flash('error','Unable to post. Posting Unbalanced.');
            return false;
        } else {
            $Journal = Journal::journalCode("DV");

            $GL = GeneralLedger::updateOrCreate([
                'column_header' => 'check_vouchers',
                'column_header_id' => $check_voucher->id
            ], [
                'journal_id' => $Journal->id,
                'reference' => "CV # " . str_pad($check_voucher->cv_no, 7, 0, STR_PAD_LEFT),
                'date' => $check_voucher->date
            ]);

            $GL->chartOfAccounts()->sync($arr_accounts);
        }

        $GL->particulars = strtoupper("CV{$check_voucher->cv_no} {$check_voucher->customer_name} DISBURSEMENT");
        $GL->save();

        return $GL;

    }

    private function hasEmptyAccounts($arr_accounts){

        if ( !isset( $arr_accounts ) ) {
            return true;
        }

        if ( count( $arr_accounts ) ) {
            foreach( $arr_accounts as $key => $value ){
                if ( empty($key) ) {
                    return true;
                }
            }
        }
        return false;
    }


}
