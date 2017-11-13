<?php

namespace App\Http\Controllers;

use App\AccountType;
use App\ChartOfAccount;
use App\Journal;
use App\Library\LoanComputation;
use App\Loan;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;


class ChartOfAccountsController extends Controller
{
    public function __construct(){

        $route_url = "chart-of-accounts";
        $route_view = str_replace("-","_",$route_url);

        $this->route_url = $route_url;
        $this->route_view = $route_view;

        $account_types = AccountType::all()->pluck('account_type_desc','id');

        $search_data = [ 'search_url' => $route_url ];

        $this->arr_rules  = [
            'account_desc' => 'required',
            'account_code' => 'required',
            'account_type_id' => 'required',
        ];

        return view()->share(compact([
            'search_data','route_url','route_view',
            'journals',
            'account_types'
        ]));
    }

    public function index(Request $request){
        $keyword = $request->input('keyword');

        if ( $keyword ) {
            ${$this->route_view} = ChartOfAccount::where('account_desc','like',"%$keyword%")
                ->orWhere('account_code','=',"$keyword")
                ->orderBy('account_code')
                ->paginate();
        } else {
            ${$this->route_view} = ChartOfAccount::orderBy('account_code')->paginate();
        }

        return view("{$this->route_view}.search_{$this->route_view}",compact([
            $this->route_view
        ]));
    }

    public function create(){

        return view("{$this->route_view}.{$this->route_view}",compact([
        ]));
    }

    public function store(Request $request){

        $this->arr_rules['account_code'] = 'required|unique:chart_of_accounts,account_code';

        $this->validate($request,$this->arr_rules);

        $ChartOfAccount = ChartOfAccount::create($request->all());

        return Redirect::to("/{$this->route_url}/{$ChartOfAccount->id}/edit")->with('flash_message','Information Saved');;
    }

    public function edit(ChartOfAccount $chart_of_account){
        return view("{$this->route_view}.{$this->route_view}", compact([
            substr($this->route_view,0,-1)
        ]));

    }

    public function update(Request $request, ChartOfAccount $chart_of_account){

        $this->arr_rules['account_code'] = "required|unique:chart_of_accounts,account_code,{$chart_of_account->id},id";
        $this->validate($request,$this->arr_rules);
        $chart_of_account->update($request->all());
        return Redirect::to("/{$this->route_url}/{$chart_of_account->id}/edit")->with('flash_message','Information Saved')->with('flash_message','Information Saved');;
    }

    public function destroy(ChartOfAccount $chart_of_account){
        $chart_of_account->delete();
        return Redirect::to("/{$this->route_url}")->with('flash_message','Information Deleted');

    }

    public function getChartOfAccounts(){
        return ChartOfAccount::all();
    }

    public function getLoanFromAccountCode($account_code){
        $Account = ChartOfAccount::whereAccountCode($account_code)->first();

        if ( $Account && $Account->loan ) {
            $Loan = $Account->loan;
            $Loan = Loan::with('customer')->whereId($Loan->id)->first();

            $LoanComputation = new LoanComputation($Loan->id);
            $Loan->current_balance = $LoanComputation->getCurrentBalance();
            $Loan->uii_balance = $LoanComputation->getUIIBalance();
            $Loan->rff_balance = $LoanComputation->getRFFBalance();
            $Loan->ar_balance = $LoanComputation->getARBalance();

            return $Loan;

        }

        return null;
    }

    public function getAccountFromAccountCode($account_code){
        $Account = ChartOfAccount::where('account_code','=',$account_code)->first();
        return response()->json($Account);
    }

    public function saveAccount(Request $request){

        $Account = ChartOfAccount::create($request->all());

        return response()->json($Account);
    }
}
