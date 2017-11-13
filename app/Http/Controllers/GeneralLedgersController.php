<?php

namespace App\Http\Controllers;

use App\AccountType;
use App\ChartOfAccount;
use App\Customer;
use App\GeneralLedger;
use App\Journal;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;

class GeneralLedgersController extends Controller
{
    public $arr_rules;
    public $route;

    public function __construct(){
        $route = "general-ledgers";
        $this->route = $route;

        $this->arr_rules  = [
            'journal_id' => 'required',
            'date' => 'required'
        ];

        $journals = Journal::all()->pluck('journal_desc','id')->prepend('Select Journal', '');
        $chart_of_accounts = ChartOfAccount::orderBy('account_code')->get()->pluck('label','id');
        $account_types = AccountType::all()->pluck('account_type_desc','id');

        $search_data = [ 'search_url' => $this->route ];

        return view()->share(compact([
            'search_data',
            'route',

            /**
             * add additonal variables below
             */

            'chart_of_accounts', 'journals', 'account_types'
        ]));
    }

    public function index(Request $request){
        $keyword = $request->input('keyword');

        $general_ledgers = GeneralLedger::orderBy('id','desc');

        if ( $keyword ) {
            $general_ledgers = $general_ledgers->where('id', 'like', "%$keyword%");
        }

        if ( $request->has('search_id') ) {
            $general_ledgers = $general_ledgers->where('id',$request->input('search_id'));
        }

        if ( $request->has(['search_from_date','search_to_date']) ) {
            $general_ledgers = $general_ledgers->whereBetween('date',[$request->input('search_from_date'),$request->input('search_to_date')]);
        }

        if ( $request->has('search_reference') ) {
            $general_ledgers = $general_ledgers->where('reference','like','%' . $request->input('search_reference') . '%');
        }

        if ( $request->has('search_particulars') ) {
            $general_ledgers = $general_ledgers->where('particulars','like','%' . $request->input('search_particulars') . '%');
        }

        if ( $request->has('search_journal_id') ) {
            $general_ledgers = $general_ledgers->where('journal_id','=',$request->input('search_journal_id'));
        }



        $general_ledgers = $general_ledgers->paginate();

        return view(str_replace("-","_",$this->route) . ".search_" . str_replace("-","_",$this->route),compact([
            'general_ledgers'
        ]));
    }

    public function create(){

        return view(str_replace("-","_",$this->route).".".str_replace("-","_",$this->route),compact([
        ]));
    }

    public function store(Request $request){
        //dd($request->all());
        $this->validate($request,$this->arr_rules);
        $GeneralLedger = GeneralLedger::create($request->all());

        if ( $request->has('general_ledger_details') ) {
            $arr_accounts = [];
            foreach ( $request->input('general_ledger_details')['chart_of_account_id']  as $i => $chart_of_account_id) {
                $arr_accounts[$chart_of_account_id] = [
                    'debit' => empty($request->input('general_ledger_details')['debit'][$i]) ? 0 : $request->input('general_ledger_details')['debit'][$i],
                    'credit' => empty($request->input('general_ledger_details')['credit'][$i]) ? 0 : $request->input('general_ledger_details')['credit'][$i],
                    'chart_of_account_id' => $request->input('general_ledger_details')['chart_of_account_id'][$i],
                    //'description' => $request->input('general_ledger_details')['description'][$i]
                ];
            }

            $GeneralLedger->chartOfAccounts()->sync($arr_accounts);


        }

        return Redirect::to("/{$this->route}/{$GeneralLedger->id}/edit")->with('flash_message','Information Saved');
    }

    public function edit(GeneralLedger $general_ledger){
        return view(str_replace("-","_",$this->route).".".str_replace("-","_",$this->route),compact([
            'general_ledger'
        ]));

    }

    public function update(Request $request, GeneralLedger $general_ledger){

        //dd($request->all());

        $this->validate($request,$this->arr_rules);
        $general_ledger->update($request->all());

        if ( $request->has('general_ledger_details') ) {
            $arr_accounts = [];
            foreach ( $request->input('general_ledger_details')['chart_of_account_id']  as $i => $chart_of_account_id) {
                $arr_accounts[$chart_of_account_id] = [
                    'debit' => empty($request->input('general_ledger_details')['debit'][$i]) ? 0 : $request->input('general_ledger_details')['debit'][$i],
                    'credit' => empty($request->input('general_ledger_details')['credit'][$i]) ? 0 : $request->input('general_ledger_details')['credit'][$i],
                    'chart_of_account_id' => $request->input('general_ledger_details')['chart_of_account_id'][$i],
                    'description' => null
                ];

            }

            $general_ledger->chartOfAccounts()->sync($arr_accounts);
        }

        return Redirect::to("/{$this->route}/{$general_ledger->id}/edit")->with('flash_message','Information Saved');
    }

    public function destroy(GeneralLedger $general_ledger){
        $general_ledger->delete();

        return Redirect::to("/{$this->route}")->with('flash_message','GL has been Deleted');
    }

    public function generalLedgerDetails(GeneralLedger $general_ledger){
        return $general_ledger->chartOfAccounts()->orderBy('account_code')->get();
    }

    public function deleteDetail(GeneralLedger $general_ledger, $account) {
        $general_ledger->chartOfAccounts()->detach($account);
    }
}
