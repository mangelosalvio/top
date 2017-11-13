<?php

namespace App\Http\Controllers;

use App\AccountType;
use App\Bank;
use App\ChartOfAccount;
use App\CheckVoucher;
use App\Collection;
use App\GeneralLedger;
use App\Journal;
use App\Library\LoanComputation;
use App\Loan;
use App\Setting;
use Carbon\Carbon;
use Faker\Provider\DateTime;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use stdClass;

class ReportsController extends Controller
{

    public function __construct(){
        $chart_of_accounts = ChartOfAccount::orderBy('account_code')->get()->pluck('label','id');
        return view()->share(compact([
            'chart_of_accounts'
        ]));
    }

    /**
     * TRIAL BALANCE
     */
    public function trialBalance(){
        return view('reports.trial_balance', compact([
        ]));
    }

    public function generateTrialBalance(Request $request){

        $url = url("/print-trial-balance?from_date={$request->input('from_date')}
        &to_date={$request->input('to_date')}");

        return view('reports.trial_balance', compact([
            'url'
        ]));
    }

    public function printTrialBalance(Request $request){
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');

        $Accounts = ChartOfAccount::orderBy('account_code')->get();
        $day_before_to_date = Carbon::parse($from_date)->subDay()->toDateString();

        $Accounts->each(function($Account) use ($from_date, $to_date){
            $day_before_to_date = Carbon::parse($from_date)->subDay()->toDateString();

            $Account->beg_bal = $this->level3DebitCredit($Account->account_code, null, $day_before_to_date);
            $Account->current = $this->level3DebitCredit($Account->account_code, $from_date, $to_date);

            $Account->ending_bal = new stdClass();
            $Account->ending_bal->debit = $Account->beg_bal->debit + $Account->current->debit;
            $Account->ending_bal->credit = $Account->beg_bal->credit + $Account->current->credit;

            if ( in_array($Account->accountType->account_type_code, AccountType::$NORMAL_DEBIT) ) {
                $Account->balance = $Account->ending_bal->debit - $Account->ending_bal->credit;
            } else {
                $Account->balance = $Account->ending_bal->credit - $Account->ending_bal->debit;
            }

        });

        $Summary = new stdClass();
        $Summary->beg_bal = $this->level3DebitCredit('', null, $day_before_to_date);
        $Summary->current = $this->level3DebitCredit('', $from_date, $to_date);

        $Summary->ending_bal = new stdClass();
        $Summary->ending_bal->debit = $Summary->beg_bal->debit + $Summary->current->debit;
        $Summary->ending_bal->credit = $Summary->beg_bal->credit + $Summary->current->credit;

        $Summary->balance = $Summary->ending_bal->debit - $Summary->ending_bal->credit;

        return view('reports.print_trial_balance',compact([
            'from_date',
            'to_date',
            'total_debit',
            'total_credit',
            'Accounts',
            'total_balance',
            'Summary'
        ]));
    }

    public function generalLedgerActivityReport(){

        return view('reports.general_ledger_activity_report', compact([
            'chart_of_accounts'
        ]));
    }

    public function generateGeneralLedgerActivityReport(Request $request){
        $this->validate($request,[
           'chart_of_account_id' => 'required',
           'from_date' => 'required',
           'to_date' => 'required',
        ],[
            'chart_of_account_id.required' => 'Account Required'
        ]);

        $url = url("/print-general-ledger-activity-report?from_date={$request->input('from_date')}
        &to_date={$request->input('to_date')}&chart_of_account_id={$request->input('chart_of_account_id')}");

        return view('reports.general_ledger_activity_report', compact([
            'url'
        ]));
    }

    public function printGeneralLedgerActivityReport(Request $request){
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');
        $day_before_from_date = Carbon::parse($from_date)->subDay()->toDateString();
        $chart_of_account_id = $request->input('chart_of_account_id');

        $Account = ChartOfAccount::find($chart_of_account_id);

        $account_type = in_array($Account->accountType->account_type_code, [ 'A','E' ])  ? 'debit' : 'credit' ;

        if ( $account_type == 'debit' ) {
            $Account->beg_balance_debit = $this->level3Balance($Account->account_code,null,$day_before_from_date,$account_type);
            $Account->beg_balance_credit = 0;
            $balance = $Account->beg_balance_debit;
        } else {
            $Account->beg_balance_credit = $this->level3Balance($Account->account_code,null,$day_before_from_date,$account_type);
            $Account->beg_balance_debit = 0;
            $balance = $Account->beg_balance_credit;
        }

        $AccountActivities = $this->accountActivity($Account->id,$from_date,$to_date);

        foreach ( $AccountActivities as $Activity ) {
            if ($account_type == 'debit') {
                $balance += $Activity->debit;
                $balance -= $Activity->credit;
                $Activity->balance = $balance;
            } else {
                $balance += $Activity->credit;
                $balance -= $Activity->debit;
                $Activity->balance = $balance;
            }
        }
        //dd($AccountActivities);

        return view('reports.print_general_ledger_activity_report',compact([
            'from_date',
            'to_date',
            'Account',
            'AccountActivities'
        ]));
    }

    /**
     * JOURNAL LISTINGS
     */
    public function journalListings(){

        $journals = Journal::all()->pluck('journal_desc','id');

        return view('reports.journal_listings', compact([
            'journals'
        ]));
    }

    public function generateJournalListings(Request $request){

        $journals = Journal::all()->pluck('journal_desc','id');

        $url = url("/print-journal-listings?from_date={$request->input('from_date')}&to_date={$request->input('to_date')}&journal_id={$request->input('journal_id')}");

        return view('reports.journal_listings', compact([
            'url',
            'journals'
        ]));
    }

    public function printJournalListings(Request $request){
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');
        $journal_id = $request->input('journal_id');

        $Ledgers = GeneralLedger::with('chartOfAccounts')
            ->whereBetween('date',[$from_date, $to_date]);

        if ( $journal_id ) {
            $Ledgers->whereJournalId($journal_id);
        }

        $Ledgers = $Ledgers->orderBy('date')->get();
        //dd($Ledgers->toArray());
        return view('reports.print_journal_listings',compact([
            'from_date',
            'to_date',
            'Ledgers'
        ]));
    }

    /**
     * INCOME STATEMENT
     */
    public function incomeStatement(){
        return view('reports.acctg.income_statement', compact([
        ]));
    }

    public function generateIncomeStatement(Request $request){

        $url = url("/print-income-statement?month={$request->input('month')}&year={$request->input('year')}");

        return view('reports.acctg.income_statement', compact([
            'url'
        ]));
    }

    public function printIncomeStatement(Request $request){
        $year = $request->input('year');
        $month = $request->input('month');


        $first_day_of_year = Carbon::create($year,1,1)->toDateString();
        $from_date = Carbon::create($year,$month,1)->toDateString();
        $to_date = Carbon::create($year,$month,1)->endOfMonth()->toDateString();

        $last_day_previous_month = Carbon::parse($from_date)->subDay()->toDateString();

        $RevenueAccounts = ChartOfAccount::whereRaw("LENGTH(account_code) <= 5")
            ->whereRaw("LEFT(account_code,1) = 4")
            ->orderBy('account_code')->get();

        $RevenueAccounts->each(function($Account) use ($from_date, $to_date, $first_day_of_year, $last_day_previous_month){
            $Account->previous_month_debit = $this->accountCodeTotal($Account->account_code,$first_day_of_year,$last_day_previous_month,'debit');
            $Account->previous_month_credit = $this->accountCodeTotal($Account->account_code,$first_day_of_year,$last_day_previous_month,'credit');

            $Account->current_month_debit = $this->accountCodeTotal($Account->account_code,$from_date,$to_date,'debit');;
            $Account->current_month_credit = $this->accountCodeTotal($Account->account_code,$from_date,$to_date,'credit');;

            $Account->balance_to_date_debit = $Account->previous_month_debit + $Account->current_month_debit;
            $Account->balance_to_date_credit = $Account->previous_month_credit + $Account->current_month_credit;
        });

        $TotalRevenue = new stdClass();
        $TotalRevenue->previous_month_debit = $this->accountCodeTotal('4',$first_day_of_year,$last_day_previous_month,'debit');
        $TotalRevenue->previous_month_credit = $this->accountCodeTotal('4',$first_day_of_year,$last_day_previous_month,'credit');

        $TotalRevenue->current_month_debit = $this->accountCodeTotal('4',$from_date,$to_date,'debit');
        $TotalRevenue->current_month_credit = $this->accountCodeTotal('4',$from_date,$to_date,'credit');

        $TotalRevenue->balance_to_date_debit = $TotalRevenue->previous_month_debit + $TotalRevenue->current_month_debit;
        $TotalRevenue->balance_to_date_credit = $TotalRevenue->previous_month_credit + $TotalRevenue->current_month_credit;


        /*$RevenueAccounts = $RevenueAccounts->filter(function($Account){
            return $Account->amount != 0;
        });*/



        $ExpenseAccounts = ChartOfAccount::whereRaw("LENGTH(account_code) <= 5")
            ->whereRaw("LEFT(account_code,1) = 5")
            ->orderBy('account_code')->get();

        $ExpenseAccounts->each(function($Account) use ($from_date, $to_date, $first_day_of_year, $last_day_previous_month){
            $Account->previous_month_debit = $this->accountCodeTotal($Account->account_code,$first_day_of_year,$last_day_previous_month,'debit');;
            $Account->previous_month_credit = $this->accountCodeTotal($Account->account_code,$first_day_of_year,$last_day_previous_month,'credit');;

            $Account->current_month_debit = $this->accountCodeTotal($Account->account_code,$from_date,$to_date,'debit');;
            $Account->current_month_credit = $this->accountCodeTotal($Account->account_code,$from_date,$to_date,'credit');;

            $Account->balance_to_date_debit = $Account->previous_month_debit + $Account->current_month_debit;
            $Account->balance_to_date_credit = $Account->previous_month_credit + $Account->current_month_credit;
        });

        /*$ExpenseAccounts = $ExpenseAccounts->filter(function($Account){
            return $Account->amount != 0;
        });*/

        $TotalExpense = new stdClass();
        $TotalExpense->previous_month_debit = $this->accountCodeTotal('5',$first_day_of_year,$last_day_previous_month,'debit');
        $TotalExpense->previous_month_credit = $this->accountCodeTotal('5',$first_day_of_year,$last_day_previous_month,'credit');

        $TotalExpense->current_month_debit = $this->accountCodeTotal('5',$from_date,$to_date,'debit');
        $TotalExpense->current_month_credit = $this->accountCodeTotal('5',$from_date,$to_date,'credit');

        $TotalExpense->balance_to_date_debit = $TotalExpense->previous_month_debit + $TotalExpense->current_month_debit;
        $TotalExpense->balance_to_date_credit = $TotalExpense->previous_month_credit + $TotalExpense->current_month_credit;

        $total_expenses = $ExpenseAccounts->sum(function($Account){
            return $Account->amount;
        });


        return view('reports.acctg.print_income_statement',compact([
            'from_date',
            'to_date',
            'total_revenue',
            'RevenueAccounts',
            'ExpenseAccounts',
            'total_expenses',
            'TotalRevenue',
            'TotalExpense'
        ]));
    }

    private function total($chart_of_account_id,$from_date,$to_date,$column = 'debit'){
        $GL = DB::table('general_ledgers as g')
            ->join('chart_of_account_general_ledger as c','g.id','=','c.general_ledger_id')
            ->whereNull('deleted_at')
            ->where('c.chart_of_account_id','=',$chart_of_account_id);

        if ( $from_date == null ) {
            $GL = $GL->where('date','<=',$to_date);
        } else {
            $GL = $GL->whereBetween('date',[
                $from_date, $to_date
            ]);
        }

        $GL = $GL->select(DB::raw("ifnull(sum(debit),0) as debit"), DB::raw("ifnull(sum(credit),0) as credit"))->first();

        if ( $GL ) {
            return $GL->{$column};
        }

        return 0;
    }

    private function accountCodeTotal($account_code,$from_date,$to_date,$column = 'debit'){

        /**
         * return 0, if month is january, so that previous amount will be 0
         */

        if ( Carbon::parse($from_date)->year != Carbon::parse($to_date)->year ) {
            return 0;
        }

        $GL = DB::table('general_ledgers as g')
            ->join('chart_of_account_general_ledger as c','g.id','=','c.general_ledger_id')
            ->join('chart_of_accounts as ca','ca.id','=','c.chart_of_account_id')
            ->whereNull('ca.deleted_at')
            ->whereNull('g.deleted_at')
            ->where('ca.account_code','like',"$account_code%")
            ->whereBetween('date',[
                $from_date, $to_date
            ])
            ->select(DB::raw("ifnull(sum(debit),0) as debit"), DB::raw("ifnull(sum(credit),0) as credit"))->first();

        if ( $GL ) {
            return $GL->{$column};
        }

        return 0;
    }


    /**
     * BALANCE SHEET
     */
    public function balanceSheet(){
        return view('reports.acctg.balance_sheet', compact([
        ]));
    }

    public function generateBalanceSheet(Request $request){

        $url = url("/print-balance-sheet?month={$request->input('month')}&year={$request->input('year')}");

        return view('reports.acctg.balance_sheet', compact([
            'url'
        ]));
    }

    public function printBalanceSheet(Request $request){

        $year = $request->input('year');
        $month = $request->input('month');

        $first_day_of_year = Carbon::create($year,1,1)->toDateString();
        $from_date = Carbon::create($year,$month,1)->toDateString();
        $to_date = Carbon::create($year,$month,1)->endOfMonth()->toDateString();
        $last_day_previous_month = Carbon::parse($from_date)->subDay()->toDateString();

        /**
         * ASSETS
         */

        $AssetAccounts = ChartOfAccount::whereRaw("LENGTH(account_code) <= 5")
            ->whereRaw("LEFT(account_code,1) = 1")
            ->orderBy('account_code')->get();

        $AssetAccounts->each(function($Account) use ($from_date,$to_date, $last_day_previous_month, $year){

            $Account->previous_month_amount = $this->level3Balance($Account->account_code,null,$last_day_previous_month,'debit');
            $Account->current_month_amount = $this->level3Balance($Account->account_code,$from_date,$to_date,'debit');
            $Account->balance_to_date = $Account->previous_month_amount + $Account->current_month_amount;

        });

        $TotalAssets = new stdClass();
        $TotalAssets->previous_month_amount = $this->level3Balance(1,null,$last_day_previous_month,'debit');
        $TotalAssets->current_month_amount = $this->level3Balance(1,$from_date,$to_date,'debit');
        $TotalAssets->balance_to_date = $TotalAssets->previous_month_amount + $TotalAssets->current_month_amount;

        /**
         * LIABILITIES
         */

        $LiabilityAccounts = ChartOfAccount::whereRaw("LENGTH(account_code) <= 5")
            ->whereRaw("LEFT(account_code,1) = 2")
            ->orderBy('account_code')->get();

        $LiabilityAccounts->each(function($Account) use ($from_date,$to_date, $last_day_previous_month, $year){
            $Account->previous_month_amount = $this->level3Balance($Account->account_code,null,$last_day_previous_month,'credit');
            $Account->current_month_amount = $this->level3Balance($Account->account_code,$from_date,$to_date,'credit');
            $Account->balance_to_date = $Account->previous_month_amount + $Account->current_month_amount;

        });

        $TotalLiabilities = new stdClass();
        $TotalLiabilities->previous_month_amount = $this->level3Balance(2,null,$last_day_previous_month,'credit');
        $TotalLiabilities->current_month_amount = $this->level3Balance(2,$from_date,$to_date,'credit');
        $TotalLiabilities->balance_to_date = $TotalLiabilities->previous_month_amount + $TotalLiabilities->current_month_amount;

        /**
         * EQUITY
         */

        $EquityAccounts = ChartOfAccount::whereRaw("LENGTH(account_code) <= 5")
            ->whereRaw("LEFT(account_code,1) = 3")
            ->orderBy('account_code')->get();

        $EquityAccounts->each(function($Account) use ($from_date,$to_date,$last_day_previous_month, $year){

            $Account->previous_month_amount = $this->level3Balance($Account->account_code,null,$last_day_previous_month,'credit');
            $Account->current_month_amount = $this->level3Balance($Account->account_code,$from_date,$to_date,'credit');
            $Account->balance_to_date = $Account->previous_month_amount + $Account->current_month_amount;

            if ( $Account->id == Setting::account("RETAINED_EARNINGS")->id ) {
                $Account->previous_month_amount += $this->getPreviousNetIncome(Carbon::parse($to_date)->year);
            }
        });


        $TotalEquity = new stdClass();

        $TotalEquity->previous_month_amount = 0;
        $TotalEquity->previous_month_amount = $this->level3Balance(3,null,$last_day_previous_month,'credit');
        $TotalEquity->current_month_amount = $this->level3Balance(3,$from_date,$to_date,'credit');
        $TotalEquity->balance_to_date = $TotalEquity->previous_month_amount + $TotalEquity->current_month_amount;

        $NetIncome = new stdClass();

        /**
         * Net income is 0 for the beginning of the year
         */
        if ( Carbon::parse($last_day_previous_month)->year != $year ) {
            $NetIncome->previous_month_amount = 0;
        } else {
            $NetIncome->previous_month_amount = $this->getIncome(Carbon::create($year,1,1)->toDateString(), $last_day_previous_month);
        }

        $NetIncome->current_month_amount = $this->getIncome($from_date, $to_date);
        $NetIncome->balance_to_date = $NetIncome->previous_month_amount + $NetIncome->current_month_amount;

        $TotalLiabilityEquity = new stdClass();

        $TotalLiabilityEquity->previous_month_amount = $TotalLiabilities->previous_month_amount + $TotalEquity->previous_month_amount + $NetIncome->previous_month_amount;
        $TotalLiabilityEquity->current_month_amount = $TotalLiabilities->current_month_amount + $TotalEquity->current_month_amount + $NetIncome->current_month_amount;
        $TotalLiabilityEquity->balance_to_date = $TotalLiabilities->balance_to_date + $TotalEquity->balance_to_date + $NetIncome->balance_to_date;

        return view('reports.acctg.print_balance_sheet',compact([
            'from_date',
            'to_date',
            'month',
            'year',
            'AssetAccounts',
            'LiabilityAccounts',
            'EquityAccounts',
            'TotalAssets',
            'TotalLiabilities',
            'TotalEquity',
            'NetIncome',
            'TotalLiabilityEquity'
        ]));
    }

    private function level3Balance($account_code,$from_date,$to_date,$column = 'debit'){
        $GL = DB::table('general_ledgers as g')
            ->join('chart_of_account_general_ledger as c','g.id','=','c.general_ledger_id')
            ->join('chart_of_accounts as ca','ca.id','=','c.chart_of_account_id')
            ->whereNull('g.deleted_at')
            ->whereNull('ca.deleted_at')
            ->where("ca.account_code",'like',"$account_code%")
            ->select(DB::raw("ifnull(sum(debit),0) as debit"), DB::raw("ifnull(sum(credit),0) as credit"));

        if ( $from_date == null ) {
            $GL = $GL->where('date','<=',$to_date);
        } else {
            $GL = $GL->whereBetween('date',[$from_date, $to_date]);
        }


        $GL = $GL->first();

        if ( $column == "debit" ) {
            return $GL->debit - $GL->credit;
        } else {
            return $GL->credit - $GL->debit;
        }
    }

    private function level3DebitCredit($account_code,$from_date,$to_date){
        $GL = DB::table('general_ledgers as g')
            ->join('chart_of_account_general_ledger as c','g.id','=','c.general_ledger_id')
            ->join('chart_of_accounts as ca','ca.id','=','c.chart_of_account_id')
            ->whereNull('g.deleted_at')
            ->whereNull('ca.deleted_at')
            ->where("ca.account_code",'like',"$account_code%")
            ->select(DB::raw("ifnull(sum(debit),0) as debit"), DB::raw("ifnull(sum(credit),0) as credit"));

        if ( $from_date == null ) {
            $GL = $GL->where('date','<=',$to_date);
        } else {
            $GL = $GL->whereBetween('date',[$from_date, $to_date]);
        }


        $GL = $GL->first();

        return $GL;
    }

    private function accountActivity($chart_of_account_id,$from_date,$to_date){
        $GL = DB::table('general_ledgers as g')
            ->join('chart_of_account_general_ledger as c','g.id','=','c.general_ledger_id')
            ->join('chart_of_accounts as ca','ca.id','=','c.chart_of_account_id')
            ->whereNull('g.deleted_at')
            ->whereNull('ca.deleted_at')
            ->where("ca.id",'=',$chart_of_account_id);

        if ( $from_date == null ) {
            $GL = $GL->where('date','<=',$to_date);
        } else {
            $GL = $GL->whereBetween('date',[$from_date, $to_date]);
        }

        return $GL->orderBy('date')
            ->orderBy('g.id')
            ->select('account_code','account_desc','g.id','g.date','particulars','debit','credit')
            ->get();
    }


    private function balance($chart_of_account_id,$date,$column = 'debit'){
        $GL = DB::table('general_ledgers as g')
            ->join('chart_of_account_general_ledger as c','g.id','=','c.general_ledger_id')
            ->whereNull('deleted_at')
            ->where('c.chart_of_account_id','=',$chart_of_account_id)
            ->where('date','<=',$date)
            ->select(DB::raw("ifnull(sum(debit),0) as debit"), DB::raw("ifnull(sum(credit),0) as credit"))->first();

        if ( $column == "debit" ) {
            return $GL->debit - $GL->credit;
        } else {
            return $GL->credit - $GL->debit;
        }
    }

    private function getPreviousNetIncome($year)
    {
        $income = DB::table('general_ledgers as g')
            ->join('chart_of_account_general_ledger as c','g.id','=','c.general_ledger_id')
            ->join('chart_of_accounts as ca', 'ca.id','=','c.chart_of_account_id')
            ->join('account_types as a','a.id','=','ca.account_type_id')
            ->whereAccountTypeCode('I')
            ->whereNull('g.deleted_at')
            ->whereNull('ca.deleted_at')
            ->whereYear('date','<',$year)
            ->select(DB::raw("ifnull(sum(credit-debit),0) as amount"))->first()->amount;

        $expenses = DB::table('general_ledgers as g')
            ->join('chart_of_account_general_ledger as c','g.id','=','c.general_ledger_id')
            ->join('chart_of_accounts as ca', 'ca.id','=','c.chart_of_account_id')
            ->join('account_types as a','a.id','=','ca.account_type_id')
            ->whereAccountTypeCode('E')
            ->whereNull('g.deleted_at')
            ->whereNull('ca.deleted_at')
            ->whereYear('date','<',$year)
            ->select(DB::raw("ifnull(sum(debit-credit),0) as amount"))->first()->amount;

        return $income - $expenses;
    }

    private function getIncome($from_date, $to_date)
    {

        $income = DB::table('general_ledgers as g')
            ->join('chart_of_account_general_ledger as c','g.id','=','c.general_ledger_id')
            ->join('chart_of_accounts as ca', 'ca.id','=','c.chart_of_account_id')
            ->join('account_types as a','a.id','=','ca.account_type_id')
            ->whereAccountTypeCode('I')
            ->whereNull('g.deleted_at')
            ->whereNull('ca.deleted_at');

        if ( $from_date == null ) {
            $income = $income->where('date',"<=",$to_date);
        } else {
            $income = $income->whereBetween('date',[$from_date, $to_date]);
        }

        $income = $income->select(DB::raw("ifnull(sum(credit-debit),0) as amount"))->first()->amount;

        $expenses = DB::table('general_ledgers as g')
            ->join('chart_of_account_general_ledger as c','g.id','=','c.general_ledger_id')
            ->join('chart_of_accounts as ca', 'ca.id','=','c.chart_of_account_id')
            ->join('account_types as a','a.id','=','ca.account_type_id')
            ->whereAccountTypeCode('E')
            ->whereNull('g.deleted_at')
            ->whereNull('ca.deleted_at');

        if ( $from_date == null ) {
            $expenses = $expenses->where('date',"<=",$to_date);
        } else {
            $expenses = $expenses->whereBetween('date',[$from_date, $to_date]);
        }

        $expenses = $expenses->select(DB::raw("ifnull(sum(debit-credit),0) as amount"))->first()->amount;

        return $income - $expenses;
    }

    /**
     * AGING OF ACCOUNTS RECEIVALBES
     */

    public function agingOfAccountsReceivables(){
        return view('reports.acctg.aging_of_accounts_receivables', compact([
        ]));
    }

    public function generateAgingOfAccountsReceivables(Request $request){

        $url = url("/print-aging-of-accounts-receivables?month={$request->input('month')}&year={$request->input('year')}");

        return view('reports.acctg.aging_of_accounts_receivables', compact([
            'url'
        ]));
    }

    public function printAgingOfAccountsReceivables(Request $request){

        $year = $request->input('year');
        $month = $request->input('month');

        $from_date = Carbon::create($year,$month,1)->toDateString();
        $to_date = Carbon::create($year,$month,1)->endOfMonth()->toDateString();

        $one_month_before = Carbon::parse($to_date)->subMonth()->toDateString();

        $Loans = Loan::select('loans.*',DB::raw("trim(concat(last_name,', ',first_name,' ',middle_name)) as customer_name"))
            ->join('customers','customers.id','=','loans.customer_id')
            ->where('date_purchased','<=',$one_month_before)
            ->orderBy('customer_name')
            ->get();

        $Loans->each(function($Loan) use ($to_date){
            $LoanComputation  = new LoanComputation($Loan->id);
            $Loan->outstanding_balance = $LoanComputation->getCurrentBalance();
            $Loan->outstanding_interest = $LoanComputation->getUIIBalance();
            $Loan->outstanding_rebate = $LoanComputation->getRFFBalance();
            $Loan->billing_for_the_month = $LoanComputation->getBillingForTheMonth($to_date);
            $Loan->balance_if_updated = $LoanComputation->getBalanceIfUpdated($to_date);
            $Loan->total_overdue = $Loan->outstanding_balance - $Loan->balance_if_updated;
            $Loan->months_overdue = $Loan->billing_for_the_month > 0 ?  round( $Loan->total_overdue / $Loan->billing_for_the_month ,2) : 0;
            $Loan->receivables = LoanComputation::getReceivables($Loan->billing_for_the_month, $Loan->total_overdue);
            $Loan->total_receivables = 0;
            foreach ($Loan->receivables as $receivable){
                $Loan->total_receivables += $receivable;
            }
        });

        $total_receivables = [];
        for ( $i = 0 ; $i < 5 ; $i++ ) {
            $total_receivables[] = $Loans->sum(function($Loan) use ($i){
                return $Loan->receivables[$i];
            });
        }

        $Loans = $Loans->filter(function($Loan){
            return $Loan->outstanding_balance > 0;
        });



        return view('reports.acctg.print_aging_of_accounts_receivables',compact([
            'from_date',
            'to_date',
            'month',
            'year',
            'Loans',
            'total_receivables'
        ]));
    }

    /**
     * AVAILMENT REPORT
     */
    public function availmentReport(){
        return view('reports.availment_report', compact([
        ]));
    }

    public function generateAvailmentReport(Request $request){

        $url = url("/print-availment-report?from_date={$request->input('from_date')}
        &to_date={$request->input('to_date')}");

        return view('reports.availment_report', compact([
            'url'
        ]));
    }

    public function printAvailmentReport(Request $request){
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');

        $Accounts = Loan::join('customers','loans.customer_id','=','customers.id')
            ->whereNotNull('date_purchased')
            ->whereNotNull('check_date')
            ->whereBetween('check_date',[$from_date,$to_date])
            ->orderBy('customers.last_name')
            ->orderBy('customers.first_name')
            ->with('customer','LrAccount')
            ->get();


        return view('reports.print_availment_report',compact([
            'from_date',
            'to_date',
            'Accounts'
        ]));
    }

    /**
     * COLLECTION REPORT
     */

    public function collectionReport(){
        return view('reports.collection_report', compact([
        ]));
    }

    public function generateCollectionReport(Request $request){

        $url = url("/print-collection-report?from_date={$request->input('from_date')}
        &to_date={$request->input('to_date')}");

        return view('reports.collection_report', compact([
            'url'
        ]));
    }

    public function printCollectionReport(Request $request){
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');

        $Accounts = Loan::join('collections','loans.id','=','collections.loan_id')
            ->whereBetween('or_date',[$from_date,$to_date])
            ->whereNull('collections.deleted_at')
            ->whereNull('loans.deleted_at')
            ->select('collections.*', 'lr_account_id','customer_id')
            ->with('customer','LrAccount')
            ->get();



        return view('reports.print_collection_report',compact([
            'from_date',
            'to_date',
            'Accounts'
        ]));
    }

    /**
     * DAILY COLLECTION REPORT
     */

    public function dailyCollectionReport(){
        return view('reports.daily_collection_report', compact([
        ]));
    }

    public function generateDailyCollectionReport(Request $request){

        $url = url("/print-daily-collection-report?date={$request->input('date')}&balance_forwarded={$request->input('balance_forwarded')}&
        penalty={$request->input('penalty')}&rebate={$request->input('rebate')}&principal={$request->input('principal')}&
        service_fees={$request->input('service_fees')}&uii={$request->input('uii')}&interest_fin_fees={$request->input('interest_fin_fees')}&prev_collection_date={$request->input('prev_collection_date')}");

        return view('reports.daily_collection_report', compact([
            'url'
        ]));
    }

    public function printDailyCollectionReport(Request $request){
        $date = $request->input('date');
        $prev_collection_date = $request->input('prev_collection_date');


        $balance = $request->except(['_token','date']);

        foreach ($balance as $key => $value) {
            $balance[$key] = empty($value) ? 0 : $value;
        }

        $BalanceForward = (object) $balance;

        /**
         * on date
         */


        $Accounts = Collection::where('or_date','=',$date)
            ->whereNull('collections.deleted_at')
            ->get();

        $Accounts = $Accounts->each(function ($Account) {

            $Account->customer = null;
            $Account->LrAccount = null;

            if ( Loan::find($Account->loan_id) != null ) {
                $Account->customer = Loan::find($Account->loan_id)->customer;
                $Account->LrAccount = Loan::find($Account->loan_id)->LrAccount;
            }

            $Account->service_income = abs($Account->rff_credit - $Account->rff_debit);
            $Account->uii = abs($Account->uii_debit - $Account->interest_income_credit);

            $Account->collection = Collection::where('id',$Account->id)
                ->with('lessAccounts','additionalAccounts')
                ->first();

        });

        //dd($Accounts->toArray());

        /**
         * Previous Collection
         */

        strlen($prev_collection_date);

        if ( !empty( $prev_collection_date ) ) {
            $PrevCollection = Loan::join('collections','loans.id','=','collections.loan_id')
                ->where('or_date','=',$prev_collection_date)
                ->select('collections.*', 'lr_account_id','customer_id')
                ->with('customer','LrAccount')
                ->get();

            $PrevCollection = $PrevCollection->each(function ($Account) {
                $Account->service_income = abs($Account->rff_credit - $Account->rff_debit);
                $Account->uii = abs($Account->uii_debit - $Account->interest_income_credit);

                $Account->collection = Collection::where('id',$Account->id)
                    ->with('lessAccounts','additionalAccounts')
                    ->first();

            });
        }

        /**
         * For Summary
         */

        /*$Summaries = DB::table('collections')
                    ->join('loans','loans.id','=','collections.loan_id')
                    ->join('collaterals','collaterals.id','=','loans.collateral_id')
                    ->rightJoin('collateral_classes','collateral_classes.id','=','collaterals.collateral_class_id')
                    ->whereNull('collections.deleted_at')
                    ->whereNull('loans.deleted_at')
                    ->whereNull('collaterals.deleted_at')
                    ->groupBy('collateral_classes.id')
                    ->groupBy('collateral_classes.class_desc')
                    ->select(DB::raw("ifnull(sum(principal_amount),0) as principal_amount, collateral_classes.class_desc"))
                    ->where('collections.or_date','=',$date);*/
        //->get();

        $Summaries = DB::select("
            SELECT
                IFNULL(SUM(principal_amount), 0) AS principal_amount,
                collateral_classes.class_desc
            FROM
                collections
                    INNER JOIN
                loans ON loans.id = collections.loan_id
                and collections.deleted_at is null
                and loans.deleted_at is null
                and collections.or_date = ?
                    INNER JOIN
                collaterals ON collaterals.id = loans.collateral_id
                    RIGHT JOIN
                collateral_classes ON collateral_classes.id = collaterals.collateral_class_id
                and
                    collaterals.deleted_at IS NULL
            GROUP BY collateral_classes.id , collateral_classes.class_desc
        ",[$date]);


        return view('reports.print_daily_collection_report',compact([
            'date',
            'Accounts',
            'BalanceForward',
            'PrevCollection',
            'prev_collection_date',
            'Summaries'
        ]));
    }
    /*
    * CASH POSITION REPORT
    */

    public function cashPositionReport(){
        return view('reports.cash_position_report', compact([
        ]));
    }

    public function generateCashPositionReport(Request $request){

        $url = url("/print-cash-position-report?date={$request->input('date')}&balance_forwarded={$request->input('balance_forwarded')}&
        penalty={$request->input('penalty')}&rebate={$request->input('rebate')}&principal={$request->input('principal')}&
        service_fees={$request->input('service_fees')}&uii={$request->input('uii')}&interest_fin_fees={$request->input('interest_fin_fees')}&prev_collection_date={$request->input('prev_collection_date')}");

        return view('reports.cash_position_report', compact([
            'url'
        ]));
    }

    public function printCashPositionReport(Request $request){
        $date = $request->input('date');

        $balance = $request->except(['_token','date']);



        /**
         * on date
         */

        $Banks = Bank::all();
        $Collections = Collection::where('or_date','=',$date)
            ->select(DB::raw("ifnull(sum(total_payment_amount),0) as total_payment_amount, min(or_no) as min_or_no, max(or_no) as max_or_no"))->first();

        $CheckVouchers = CheckVoucher::where('date',$date)
            ->join('banks','banks.id','=','check_vouchers.bank_id')
            ->select(DB::raw("date, banks.chart_of_account_id as chart_of_account_id, customer_name, cv_no, check_no, amount, check_vouchers.id"));

        $bank_chart_of_account_id = Setting::account("BANK_ACCT")->id;

        $Disbursements = Loan::where('check_date',$date)
            ->whereNotNull('check_date')
            ->join('customers','customers.id','=','loans.customer_id')
            ->select(DB::raw("date_purchased as date,$bank_chart_of_account_id as chart_of_account_id, concat(last_name,', ',first_name,' ',middle_name) as customer_name, cv_no, check_no, net_proceeds as amount , loans.id"))
            ->unionAll($CheckVouchers)->get();

        $Banks = $Banks->each(function($Bank) use ($date, $Collections, $Disbursements) {
            if ( $Bank->account->id == Setting::account("BANK_ACCT")->id ) {
                if ( $Collections->min_or_no && $Collections->max_or_no ) {
                    $Bank->collection_ref = "{$Collections->min_or_no} to {$Collections->max_or_no}";
                }
                $Bank->total_collection = $Collections->total_payment_amount;
            }

            $Bank->total_disbursements = $Disbursements->sum(function($Disbursement) use ($Bank) {
                if ( $Disbursement->chart_of_account_id == Setting::account("BANK_ACCT")->id ) {
                    return $Disbursement->amount;
                }

                return 0;
            });

            $Bank->ending_balance = $Bank->total_collection - $Bank->total_disbursements;
        });

        $Date = Carbon::parse($date);
        $first_day_of_month = Carbon::createFromDate($Date->year,$Date->month,1)->toDateString();
        $day_before = Carbon::parse($date)->subDay()->toDateString();

        $Summaries = DB::select("
            SELECT
                IFNULL(SUM(pn_amount), 0) AS pn_amount,
                IFNULL(SUM(amount), 0) AS amount,
                IFNULL(SUM(net_proceeds), 0) AS net_proceeds,
                IFNULL(SUM(service_fees), 0) AS service_fees,
                count(*) as number_of_accounts,
                collateral_classes.class_desc
            FROM
                loans INNER JOIN collaterals ON collaterals.id = loans.collateral_id
            and loans.check_date is not NULL
            and loans.check_date between ? and ?
            and loans.deleted_at is null
            and collaterals.deleted_at IS NULL
            RIGHT JOIN collateral_classes ON collateral_classes.id = collaterals.collateral_class_id
            GROUP BY collateral_classes.id , collateral_classes.class_desc
        ",[$first_day_of_month, $date]);


        $PreviousSummary = DB::select("
            SELECT
                IFNULL(SUM(pn_amount), 0) AS pn_amount,
                IFNULL(SUM(amount), 0) AS amount,
                IFNULL(SUM(net_proceeds), 0) AS net_proceeds,
                IFNULL(SUM(service_fees), 0) AS service_fees,
                count(*) as number_of_accounts
            FROM
                loans INNER JOIN collaterals ON collaterals.id = loans.collateral_id
            RIGHT JOIN collateral_classes ON collateral_classes.id = collaterals.collateral_class_id
            WHERE
              loans.check_date is not NULL
            and loans.check_date between ? and ?
            and loans.deleted_at is null
            and collaterals.deleted_at IS NULL
        ",[$first_day_of_month, $day_before]);

        $TodaySummary = DB::select("
            SELECT
                IFNULL(SUM(pn_amount), 0) AS pn_amount,
                IFNULL(SUM(amount), 0) AS amount,
                IFNULL(SUM(net_proceeds), 0) AS net_proceeds,
                IFNULL(SUM(service_fees), 0) AS service_fees,
                count(*) as number_of_accounts
            FROM
                loans INNER JOIN collaterals ON collaterals.id = loans.collateral_id
            RIGHT JOIN collateral_classes ON collateral_classes.id = collaterals.collateral_class_id
            WHERE
              loans.check_date is not NULL
            and loans.check_date between ? and ?
            and loans.deleted_at is null
            and collaterals.deleted_at IS NULL
        ",[$date, $date]);

        $TotalSummary = DB::select("
            SELECT
                IFNULL(SUM(pn_amount), 0) AS pn_amount,
                IFNULL(SUM(amount), 0) AS amount,
                IFNULL(SUM(net_proceeds), 0) AS net_proceeds,
                IFNULL(SUM(service_fees), 0) AS service_fees,
                count(*) as number_of_accounts
            FROM
                loans INNER JOIN collaterals ON collaterals.id = loans.collateral_id
                RIGHT JOIN collateral_classes ON collateral_classes.id = collaterals.collateral_class_id
            WHERE
              loans.check_date is not NULL
            and loans.check_date between ? and ?
            and loans.deleted_at is null
            and collaterals.deleted_at IS NULL
        ",[$first_day_of_month, $date]);

        $Summaries = collect($Summaries);
        $PreviousSummary = collect($PreviousSummary)->first();
        $TodaySummary = collect($TodaySummary)->first();
        $TotalSummary = collect($TotalSummary)->first();


        //dd($Disbursements->toArray());

        return view('reports.print_cash_position_report',compact([
            'date',
            'Banks',
            'Collections',
            'Disbursements',
            'DisbursementBuilder',
            'Summaries',
            'PreviousSummary',
            'TodaySummary',
            'TotalSummary'
        ]));
    }
}
