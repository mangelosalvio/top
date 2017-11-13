<?php

namespace App\Http\Controllers;

use App\ChartOfAccount;
use App\GeneralLedger;
use App\Journal;
use App\Library\NumToWords;
use App\Loan;
use App\Setting;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;

use Illuminate\Support\Facades\Session;
use Laracasts\Utilities\JavaScript\JavaScriptFacade as JavaScript;
use NumberFormatter;
use stdClass;

class ComputationSlipsController extends Controller
{
    public $arr_rules;
    public $route;

    public function __construct(){
        $route = "computation-slips";
        $this->route = $route;

        $this->arr_rules  = [

        ];

        $chart_of_accounts = ChartOfAccount::orderBy('account_code')->get()->pluck('label','id');

        $search_data = [ 'search_url' => $this->route, 'display_add_btn' => false ];

        return view()->share(compact([
            'search_data','customers','collateral_classes','trans_types','collaterals','route',
            'chart_of_accounts'
        ]));
    }

    public function index(Request $request){
        $keyword = $request->input('keyword');

        $loans = Loan::orderBy('date','desc');

        if ( $keyword ) {
            $loans = Loan::whereHas('customer', function($query) use ($keyword){
                $query->where('last_name','like',"%$keyword%")
                    ->orWhere('first_name','like',"%$keyword%")
                    ->orWhere('middle_name','like',"%$keyword%");
            })
                ->orWhere('id','like',"$keyword%");
        }

        if ( $request->has('search_id') ) {
            $loans = $loans->orWhere('id',$request->input('search_id'));
        }

        if ( $request->has('search_customer_name') ) {
            $loans = $loans->orWhereHas('customer', function($query) use ($request){
                $query->where('last_name','like',"%{$request->input('search_customer_name')}%")
                    ->orWhere('first_name','like',"%{$request->input('search_customer_name')}%")
                    ->orWhere('middle_name','like',"%{$request->input('search_customer_name')}%");
            });
        }

        if ( $request->has(['search_date_purchased_from_date','search_date_purchased_to_date']) ) {
            $loans = $loans->orWhereBetween('date_purchased',[ $request->input('search_date_purchased_from_date'),$request->input('search_date_purchased_to_date') ]);
        }

        if ( $request->has(['search_check_date_from_date','search_check_date_to_date']) ) {
            $loans = $loans->orWhere(function($query) use ($request) {
                $query->whereNotNull('check_date')
                    ->whereBetween('check_date',[ $request->input('search_check_date_from_date'),$request->input('search_check_date_to_date') ]);
            });
        }



        $loans = $loans->paginate();

        return view(str_replace("-","_",$this->route) . ".search_" . str_replace("-","_",$this->route),compact([
            'loans'
        ]));
    }

    public function create(){

        return view(str_replace("-","_",$this->route).".".str_replace("-","_",$this->route),compact([
        ]));
    }

    public function store(Request $request){

        $this->validate($request,$this->arr_rules);

        $Loan = Loan::create($request->all());

        return Redirect::to("/{$this->route}/{$Loan->id}/edit")->with('flash_message','Information Saved');
    }

    public function edit(Loan $computation_slip){

        if ( $computation_slip->is_renewal == 0 ) {
            $computation_slip->doc_stamp = ($computation_slip->pn_amount - 5000) / 5000;
            $computation_slip->doc_stamp = round((((((int)$computation_slip->doc_stamp) + 1 ) * 5 ) + 10 ) * 2,2);

            $computation_slip->mortgage_fees = $this->getMortgateFees($computation_slip);
        } else {
            $computation_slip->doc_stamp = 0;
            $computation_slip->mortgage_fees = 0;
        }

        $computation_slip->save();

        return view(str_replace("-","_",$this->route).".".str_replace("-","_",$this->route),compact([
            'computation_slip'
        ]));

    }

    public function update(Request $request, Loan $computation_slip){

        $this->validate($request,$this->arr_rules);
        $computation_slip->update($request->all());

        $other_additions = [];
        $other_deductions = [];


        if ( count( $request->input('other_additions') ) ) {
            foreach ( $request->input('other_additions')['chart_of_account_id'] as $i => $chart_of_account_id ) {
                $other_additions[$chart_of_account_id] = [
                    'amount' => $request->input('other_additions')['amount'][$i]
                ];
            }

            $computation_slip->otherAdditions()->sync($other_additions);
        }

        if ( count( $request->input('other_deductions') ) ) {
            foreach ( $request->input('other_deductions')['chart_of_account_id'] as $i => $chart_of_account_id ) {
                $other_deductions[$chart_of_account_id] = [
                    'amount' => $request->input('other_deductions')['amount'][$i]
                ];
            }
            $computation_slip->otherDeductions()->sync($other_deductions);
        }

        /**
         * if GL entry is deleted, restore
         * else GL create or update
         */

        if ( ! $computation_slip->is_balance_forwarded ) {
            $GL = GeneralLedger::withTrashed()->where([
                ['column_header','=', 'loans'],
                ['column_header_id','=',$computation_slip->id]
            ])->first();

            if ( $GL && $GL->trashed() ) {
                $GL->restore();
            }

            // post is included in update because, the computation slip is saved at loans transaction
            if (!$this->post($computation_slip)) {
                return Redirect::to("/{$this->route}/{$computation_slip->id}/edit")->with('flash_message','Unable to post to ledger. Please check accounts.');
            }

        } else {
            /**
             * delete post from GL if it is balance forwarded
             */
            $GL = GeneralLedger::where([
                ['column_header','=', 'loans'],
                ['column_header_id','=',$computation_slip->id]
            ])->first();

            if ( $GL ) {
                $GL->delete();
            }
        }

        return Redirect::to("/{$this->route}/{$computation_slip->id}/edit")->with('flash_message','Information Saved');
    }

    public function destroy(Loan $computation_slip){
        $computation_slip->delete();
        return Redirect::to("/{$this->route}")->with('flash_message','Loan Class Deleted');
    }

    private function getMortgateFees(Loan $Loan){

        $pn_amount = $Loan->pn_amount;

        $starting = 3500;
        $increment = 500;

        $amount_increments = 9;
        $amount = 84;
        do {
            #echo "$pn_amount >= $starting && $pn_amount <= $starting + $increment <br>";
            if ( $pn_amount >= $starting && $pn_amount <= $starting + $increment ) {
                return $amount;
            }

            if ( $pn_amount < 3500 ) {
                return 0;
            }

            $starting += $increment;
            #echo  $starting ." - ". ($starting + $increment) ." | $amount_increments | $amount <br>";

            if ( $starting >= 3500 && $starting < 6000 ) {
                #echo "$starting <br>";
                #echo "+9<br>";
                $increment = 500;
                $amount_increments = 9;
            } else if ( $starting >= 6000 && $starting < 30000 ) {
                #echo "+24<br>";
                $increment = 2000;
                $amount_increments = 24;
            } else if ( $starting >= 30000 && $starting < 100000 ) {
                #echo "+42<br>";
                $increment = 5000;
                $amount_increments = 42;
            } else if ( $starting >= 100000 && $starting < 500000 ) {
                #echo "+60<br>";
                $increment = 10000;
                $amount_increments = 60;
            } else if ( $starting >= 500000) {
                #echo "+90<br>";
                $increment = 20000;
                $amount_increments = 90;
            }

            $amount += $amount_increments;

        }while(true);

    }

    public function printComputationSlip(Loan $loan){
        return view('reports.print_computation_slip', compact([
            'loan'
        ]));
    }

    public function printCashVoucher(Loan $loan){

        $cash_amount = $loan->net_proceeds;
        $formatter = new NumToWords($cash_amount);
        $words = $formatter->getWord();
        return view('reports.print_cash_voucher', compact([
            'loan',
            'cash_amount',
            'words'
        ]));
    }

    public static function post(Loan $loan){

        $arr_accounts = [];
        $arr_accounts[$loan->lr_account_id] = [
            'debit' => $loan->pn_amount,
            'credit' => 0,
            'description' => NULL
        ];

        $arr_accounts[$loan->uii_account_id] = [
            'credit' => $loan->interest_amount,
            'debit' => 0,
            'description' => NULL
        ];

        $arr_accounts[$loan->rff_account_id] = [
            'credit' => $loan->rebate_amount,
            'debit' => 0,
            'description' => NULL
        ];

        $arr_accounts[Setting::account("PROC_FEE")->id] = [
            'credit' => $loan->service_fees,
            'debit' => 0,
            'description' => NULL
        ];

        $arr_accounts[Setting::account("DOC_FEE")->id] = [
            'credit' => $loan->total_doc_fees,
            'debit' => 0,
            'description' => NULL
        ];

        $arr_accounts[Setting::account("INSURANCE_PAYABLES")->id] = [
            'credit' => $loan->od_insurance_fees,
            'debit' => 0,
            'description' => NULL
        ];

        if ( $loan->otherAdditions()->count() > 0 ) {
            foreach ( $loan->otherAdditions as $i => $OtherAddition ) {

                $arr_accounts[$OtherAddition->id] = [
                    'debit' => $OtherAddition->pivot->amount,
                    'credit' => 0,
                    'description' => NULL
                ];
            }
        }


        if ( $loan->otherDeductions()->count() > 0 ) {
            foreach ($loan->otherDeductions as $OtherDeduction) {
                $arr_accounts[$OtherDeduction->id] = [
                    'credit' => $OtherDeduction->pivot->amount,
                    'debit' => 0,
                    'description' => NULL
                ];
            }
        }

        $arr_accounts[Setting::account("BANK_ACCT")->id] = [
            'credit' => $loan->net_proceeds,
            'debit' => 0,
            'description' => NULL
        ];

        $Total = new stdClass();
        $Total->debit = $Total->credit = 0;

        foreach ( $arr_accounts as $account => $arr ) {
            $Total->debit += $arr['debit'];
            $Total->credit += $arr['credit'];
        }

        if ( self::hasEmptyAccounts($arr_accounts) ) {
            return false;
        } else if ( $Total->debit != $Total->credit ) {
            Session::flash('error','Unable to post. Posting Unbalanced.');
            return false;
        } else if ( empty( $loan->check_date ) ) {
            Session::flash('error','Unable to post. Please supply check date in Computation Slip.');
            return false;
        } else {
            $Journal = Journal::journalCode("DV");

            $GL = GeneralLedger::updateOrCreate([
                'column_header' => 'loans',
                'column_header_id' => $loan->id
            ],[
                'journal_id' => $Journal->id,
                'reference' => "Loan # ".str_pad($loan->id,7,0,STR_PAD_LEFT),
                'date' => $loan->check_date
            ]);

            $GL->chartOfAccounts()->sync($arr_accounts);

            $GL->particulars = strtoupper("CV{$loan->cv_no} {$loan->customer->name} DISBURSEMENT");
            $GL->save();
        }

        return $GL;

    }

    public static function hasEmptyAccounts($arr_accounts){
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
