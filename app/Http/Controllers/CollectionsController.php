<?php

namespace App\Http\Controllers;

use App\AmortizationTable;
use App\ChartOfAccount;
use App\Collection;
use App\CollectionPenalty;
use App\GeneralLedger;
use App\Journal;
use App\Library\LoanComputation;
use App\Loan;
use App\LoanLedger;
use App\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use stdClass;

class CollectionsController extends Controller
{
    public $arr_rules;
    public $route;

    public function __construct(){

        $route = "collections";
        $this->route = $route;

        $this->arr_rules  = [
            'or_no' => 'required',
            'or_date' => 'required',
        ];

        $journals = Journal::all()->pluck('journal_desc','id');
        $chart_of_accounts = ChartOfAccount::orderBy('account_code')->get()->pluck('label','id');

        $search_data = [ 'search_url' => $this->route ];

        return view()->share(compact([
            'search_data',
            'route',

            /**
             * add additonal variables below
             */

            'chart_of_accounts', 'journals'
        ]));
    }

    public function index(Request $request){
        $keyword = $request->input('keyword');

        if ( $keyword ) {
            $collections = Collection::where('id','like',"%$keyword%")
                ->orWhereHas('loan', function($query) use ($keyword){
                    $query->whereHas('customer',function($q) use ($keyword){
                        $q->where('last_name','like',"%$keyword%")
                            ->orWhere('first_name','like',"%$keyword%")
                            ->orWhere('middle_name','like',"%$keyword%");
                    });
                })
                ->orWhere('or_no','=',"$keyword")
                ->orderBy('id','desc')
                ->paginate();
        } else {
            $collections = Collection::orderBy('id','desc')->paginate();
        }

        return view(str_replace("-","_",$this->route) . ".search_" . str_replace("-","_",$this->route),compact([
            'collections'
        ]));
    }

    public function create(){

        return view(str_replace("-","_",$this->route).".".str_replace("-","_",$this->route),compact([
        ]));
    }

    public function store(Request $request){
        //dd($request->all());
        $this->validate($request,$this->arr_rules);
        $Collection = Collection::create($request->all());

        if ( $request->has('less_other_account') ) {
            $arr_accounts = [];
            foreach ( $request->input('less_other_accounts')['chart_of_account_id']  as $i => $chart_of_account_id) {
                $arr_accounts[$chart_of_account_id] = [
                    'amount' => empty($request->input('less_other_accounts')['amount'][$i]) ? 0 : $request->input('less_other_accounts')['amount'][$i],
                ];
            }
            $Collection->lessAccounts()->sync($arr_accounts);
        }

        if ( $request->has('add_other_account') ) {
            $arr_additonal_accounts = [];
            foreach ( $request->input('add_other_account')['chart_of_account_id']  as $i => $chart_of_account_id) {
                $arr_additonal_accounts[$chart_of_account_id] = [
                    'amount' => empty($request->input('add_other_account')['amount'][$i]) ? 0 : $request->input('add_other_account')['amount'][$i],
                ];
            }

            $Collection->additionalAccounts()->sync($arr_additonal_accounts);
        }


        $Collection->penalties()->delete();
        if ( $Collection->is_penalty_computed ) {
            $this->computePenalty($Collection);
        }

        $this->updateLedger($Collection);


        if (!$this->post($Collection)) {
            return Redirect::to("/{$this->route}/{$Collection->id}/edit");
        }


        return Redirect::to("/{$this->route}/{$Collection->id}/edit")->with('flash_message','Information Saved');
    }

    public function edit(Collection $collection){
        return view(str_replace("-","_",$this->route).".".str_replace("-","_",$this->route),compact([
            'collection'
        ]));

    }

    public function update(Request $request, Collection $collection){
        $this->validate($request,$this->arr_rules);

        $collection->update($request->all());

        if ( $request->has('less_other_account') ) {
            $arr_less_accounts = [];
            foreach ( $request->input('less_other_account')['chart_of_account_id']  as $i => $chart_of_account_id) {
                $arr_less_accounts[$chart_of_account_id] = [
                    'amount' => empty($request->input('less_other_account')['amount'][$i]) ? 0 : $request->input('less_other_account')['amount'][$i],
                ];
            }

            $collection->lessAccounts()->sync($arr_less_accounts);
        }

        if ( $request->has('add_other_account') ) {
            $arr_additonal_accounts = [];
            foreach ( $request->input('add_other_account')['chart_of_account_id']  as $i => $chart_of_account_id) {
                $arr_additonal_accounts[$chart_of_account_id] = [
                    'amount' => empty($request->input('add_other_account')['amount'][$i]) ? 0 : $request->input('add_other_account')['amount'][$i],
                ];
            }

            $collection->additionalAccounts()->sync($arr_additonal_accounts);
        }

        $collection->penalties()->delete();
        if ( $collection->is_penalty_computed ) {
            $this->computePenalty($collection);
        }

        $this->updateLedger($collection);

        if (!$this->post($collection)) {
            return Redirect::to("/{$this->route}/{$collection->id}/edit");
        }

        return Redirect::to("/{$this->route}/{$collection->id}/edit")->with('flash_message','Information Saved');
    }

    public function destroy(Collection $collection){

        $collection->ledger()->delete();

        $GL = $collection->gl();

        if ( $GL ) {
            $GL->delete();
        }

        $collection->delete();
        return Redirect::to("/{$this->route}")->with('flash_message','Transaction Deleted');
    }

    public function generalLedgerDetails(GeneralLedger $general_ledger){
        return $general_ledger->chartOfAccounts;
    }

    public function deleteDetail(GeneralLedger $general_ledger, $account) {
        $general_ledger->chartOfAccounts()->detach($account);
    }

    public function getCollection(Collection $collection){
        return $collection;
    }

    public function getLoanComputation(Loan $loan, Request $request){
        $LoanComputation = new LoanComputation($loan->id);
        return $LoanComputation->compute($request->input('current_balance'), $request->input('principal_amount'));
    }

    public function lessAccounts(Collection $collection){
        return $collection->lessAccounts->each(function($Account){
            $Account->chart_of_account_id = $Account->id;
            $Account->amount = $Account->pivot->amount;
        });
    }

    public function additionalAccounts(Collection $collection){
        return $collection->additionalAccounts->each(function($Account){
            $Account->chart_of_account_id = $Account->id;
            $Account->amount = $Account->pivot->amount;
        });
    }

    public function deleteLessAccount(Collection $collection, ChartOfAccount $account){
        $collection->lessAccounts()->detach($account->id);
        return null;
    }

    public function deleteAdditionalAccount(Collection $collection, ChartOfAccount $account){
        $collection->additionalAccounts()->detach($account->id);
        return null;
    }

    private function computePenalty(Collection $collection){
        $penalty_as_of_date = Carbon::parse($collection->penalty_as_of_date);
        $last_transaction_date = $collection->last_transaction_date;

        $Table =  AmortizationTable::where('due_date','<=',$penalty_as_of_date)
            ->where('loan_id',$collection->loan_id)
            ->where('outstanding_balance','<', $collection->current_balance)
            ->get();

        $total_penalty = 0;

        $Table->each(function($Ledger) use ($collection, $total_penalty){

            if ( ($collection->current_balance - $Ledger->outstanding_balance) <=  $Ledger->installment_amount  ) {
                $Ledger->arrears = $collection->current_balance - $Ledger->outstanding_balance;
            } else {
                $Ledger->arrears = $Ledger->installment_amount;
            }

            /** compute penalty */

            /**
             * due date is greater than or equal to last transaction date
             */
            if ( Carbon::parse($Ledger->due_date)->gte(Carbon::parse($collection->last_transaction_date)) ) {
                /**
                 * due date to penalty as of date
                 */
                $Ledger->days_delayed = Carbon::parse($Ledger->due_date)->diffInDays(Carbon::parse($collection->penalty_as_of_date));
            } else {
                /**
                 * last transaction date to penalty as of date
                 */
                $Ledger->days_delayed = Carbon::parse($collection->last_transaction_date)->diffInDays(Carbon::parse($collection->penalty_as_of_date));
            }

            $Ledger->penalty = round($Ledger->arrears * $collection->penalty_rate / 100 / 30 * $Ledger->days_delayed,2);


            $collection->penalties()->create([
                'due_date' => $Ledger->due_date,
                'days_delayed' => $Ledger->days_delayed,
                'arrears' => $Ledger->arrears,
                'penalty' => $Ledger->penalty
            ]);
        });

        $collection->total_penalty = $Table->sum(function($Ledger){
            return $Ledger->penalty;
        });

        if ( $collection->penalty_disc_rate ) {
            $collection->penalty_disc_amount = round($collection->total_penalty * $collection->penalty_disc_rate / 100,2);
        } else {
            $collection->penalty_disc_amount = 0;
        }

        $collection->net_penalty_due = $collection->total_penalty - $collection->penalty_disc_amount;
        $collection->save();

        //dd($Table->toArray());

    }

    private function updateLedger(Collection $collection)
    {
        if ( $collection->loan_id == null ) return false;

        LoanLedger::updateOrCreate(
            [ 'collection_id' => $collection->id ],
            [
                'date' => $collection->or_date,
                'loan_id' => $collection->loan_id,
                'payment_amount' => $collection->total_payment_amount,
                'rebate_amount' => $collection->rff_credit,
                'outstanding_balance' => $collection->current_balance - $collection->principal_amount,
                'outstanding_interest' => $collection->uii_balance - $collection->uii_debit,
                'outstanding_rebate' => $collection->rff_balance - $collection->rff_debit
            ]
        );
    }

    private function post(Collection $collection){

        $loan = $collection->loan;

        $arr_accounts = [];

        $lr_amount = $collection->principal_amount ;

        if ( $loan ) {

            if ( $lr_amount ) {
                $arr_accounts[$loan->lr_account_id] = [
                    'credit' => $lr_amount,
                    'debit' => 0,
                    'description' => NULL
                ];
            }

            if ( $collection->rff_debit ) {
                $arr_accounts[$loan->rff_account_id] = [
                    'debit' => $collection->rff_debit,
                    'credit' => 0,
                    'description' => NULL
                ];
            }

            $service_income = $collection->rff_debit - $collection->rff_credit;
            if ( $service_income ) {
                $arr_accounts[Setting::account("SERVICE_FEES_ACCT")->id] = [
                    'credit' => $service_income,
                    'debit' => 0,
                    'description' => NULL
                ];
            }


            if ( $collection->uii_debit ) {
                $arr_accounts[$loan->uii_account_id] = [
                    'debit' => $collection->uii_debit,
                    'credit' => 0,
                    'description' => NULL
                ];
            }


            if ( $collection->interest_income_credit ) {
                $arr_accounts[Setting::account("INTEREST_FINANCING_ACCT")->id] = [
                    'credit' => $collection->interest_income_credit,
                    'debit' => 0,
                    'description' => NULL
                ];
            }


            if ( $collection->net_penalty_due ) {
                $arr_accounts[Setting::account("PENALTIES_ACCT")->id] = [
                    'credit' => $collection->net_penalty_due,
                    'debit' => 0,
                    'description' => NULL
                ];
            }

        }


        foreach ( $collection->additionalAccounts as $i => $OtherAddition ) {

            if ( isset( $arr_accounts[$OtherAddition->id] ) ) {
                $arr_accounts[$OtherAddition->id]['credit'] += $OtherAddition->pivot->amount;
            } else {
                $arr_accounts[$OtherAddition->id] = [
                    'credit' => $OtherAddition->pivot->amount,
                    'debit' => 0,
                    'description' => NULL
                ];
            }

        }

        foreach ( $collection->lessAccounts as $OtherDeduction ) {

            if ( isset( $arr_accounts[$OtherDeduction->id] ) ) {
                $arr_accounts[$OtherDeduction->id]['debit'] += $OtherDeduction->pivot->amount;
            } else {
                $arr_accounts[$OtherDeduction->id] = [
                    'debit' => $OtherDeduction->pivot->amount,
                    'credit' => 0,
                    'description' => NULL
                ];
            }
        }


        if ( $collection->cash_amount > 0 ) {

            $arr_accounts[Setting::account("COH")->id] = [
                'debit' => $collection->cash_amount,
                'credit' => 0,
                'description' => NULL
            ];
        }

        if ( $collection->check_amount > 0 ) {
            $arr_accounts[Setting::account("COCI")->id] = [
                'debit' => $collection->check_amount,
                'credit' => 0,
                'description' => NULL
            ];

        }

        $Total = new stdClass();
        $Total->debit = $Total->credit = 0;
        foreach ( $arr_accounts as $account => $arr ) {
            $Total->debit += $arr['debit'];
            $Total->credit += $arr['credit'];
        }


        if ( $Total->debit != $Total->credit ) {
            Session::flash('error','Unable to post. Posting Unbalanced.');
            return false;
        }

        if ( $this->hasEmptyAccounts($arr_accounts) ) {
            Session::flash('error','Unable to post. Please supply Accounts at Loans Masterfile.');
            return false;
        } else {
            $Journal = Journal::journalCode("CR");

            $GL = GeneralLedger::updateOrCreate([
                'column_header' => 'cash_receipts',
                'column_header_id' => $collection->id
            ], [
                'journal_id' => $Journal->id,
                'reference' => "Collection # " . str_pad($collection->id, 7, 0, STR_PAD_LEFT),
                'date' => $collection->or_date
            ]);

            $GL->chartOfAccounts()->sync($arr_accounts);

            if ( $collection->loan) {
                $GL->particulars = strtoupper("{$collection->or_no} {$collection->loan->customer->name} collection");
            } else {
                $GL->particulars = strtoupper("{$collection->or_no} {$collection->received_from} collection");
            }

            $GL->save();

        }

        return $GL;

    }

    private function hasEmptyAccounts($arr_accounts){
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
