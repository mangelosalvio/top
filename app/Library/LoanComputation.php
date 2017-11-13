<?php

namespace App\Library;


use App\AmortizationTable;
use App\ChartOfAccount;
use App\Loan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class LoanComputation
{

    private $loan_id;

    public function __construct($loan_id)
    {
        $this->loan_id = $loan_id;
        $this->Loan    = Loan::find($loan_id);
    }

    public function getCurrentBalance()
    {
        /**
         * Get outstanding balance from ledger
         */

        $GL = DB::table('general_ledgers as g')
            ->join('chart_of_account_general_ledger as c', 'g.id', '=', 'c.general_ledger_id')
            ->whereNull('deleted_at')
            ->where('c.chart_of_account_id', '=', $this->Loan->lr_account_id)
            ->select(DB::raw("ifnull(sum(debit),0) as debit"), DB::raw("ifnull(sum(credit),0) as credit"))->first();

        if ( $GL ) {
            return $GL->debit - $GL->credit;
        }

        return 0;



        /*if ($this->Loan->loanLedgers()->count() > 0) {
            return $this->Loan->loanLedgers()->latest()->first()->outstanding_balance;
        } else if ($this->Loan->is_balance_forwarded) {
            return $this->Loan->balance_forwarded;
        }*/

        return $this->Loan->pn_amount;
    }

    public function getUIIBalance()
    {

        $GL = DB::table('general_ledgers as g')
            ->join('chart_of_account_general_ledger as c', 'g.id', '=', 'c.general_ledger_id')
            ->whereNull('deleted_at')
            ->where('c.chart_of_account_id', '=', $this->Loan->uii_account_id)
            ->select(DB::raw("ifnull(sum(debit),0) as debit"), DB::raw("ifnull(sum(credit),0) as credit"))->first();

        if ( $GL ) {
            return $GL->credit - $GL->debit;
        }

        return 0;

        /*if ($this->Loan->loanLedgers()->count() > 0) {
            return $this->Loan->loanLedgers()->latest()->first()->outstanding_interest;
        }

        if ($this->Loan->is_balance_forwarded) {
            return AmortizationTable::whereLoanId($this->Loan->id)
                ->where('outstanding_balance', '>', $this->Loan->balance_forwarded)
                ->latest()
                ->first()->outstanding_interest;
        }
        return $this->Loan->interest_amount;*/
    }

    public function getRFFBalance()
    {

        $GL = DB::table('general_ledgers as g')
            ->join('chart_of_account_general_ledger as c', 'g.id', '=', 'c.general_ledger_id')
            ->whereNull('deleted_at')
            ->where('c.chart_of_account_id', '=', $this->Loan->rff_account_id)
            ->select(DB::raw("ifnull(sum(debit),0) as debit"), DB::raw("ifnull(sum(credit),0) as credit"))->first();

        if ( $GL ) {
            return $GL->credit - $GL->debit;
        }

        return 0;

        /*if ($this->Loan->loanLedgers()->count() > 0) {
            return $this->Loan->loanLedgers()->latest()->first()->outstanding_rebate;
        }

        if ($this->Loan->is_balance_forwarded) {
            return AmortizationTable::whereLoanId($this->Loan->id)
                ->where('outstanding_balance', '>', $this->Loan->balance_forwarded)
                ->latest()
                ->first()->outstanding_rebate;
        }

        return $this->Loan->rebate_amount;*/
    }

    public function getARBalance()
    {
        $ar_account_id = $this->Loan->ar_account_id;

        $GL = DB::table('general_ledgers as g')
            ->join('chart_of_account_general_ledger as c', 'g.id', '=', 'c.general_ledger_id')
            ->whereNull('deleted_at')
            ->where('c.chart_of_account_id', '=', $ar_account_id)
            ->select(DB::raw("ifnull(sum(debit),0) as debit"), DB::raw("ifnull(sum(credit),0) as credit"))->first();

        return $GL->debit - $GL->credit;
    }

    public function compute($current_balance, $principal_amount)
    {

        $Table = DB::table('amortization_tables')
            ->whereLoanId($this->loan_id)
            ->where('outstanding_balance', '<', $current_balance)
            ->where('outstanding_balance', '>=', $current_balance - $principal_amount)
            ->select(DB::raw("ifnull(sum(interest_amount),0) as interest_amount, ifnull(sum(rebate_amount),0) as rebate_amount"))
            ->groupBy('loan_id');

        return response()->json($Table->first());

    }

    public function getBillingForTheMonth($date){

        $outstanding_balance = $this->getCurrentBalance();
        $Billing =  $this->Loan->amortizationTables()->where('due_date','<=',$date)
            //->where('outstanding_balance','<=', $outstanding_balance)
            ->orderBy('due_date','desc')->first();

        if ( $Billing ) {

            /*if ( $outstanding_balance <= $Billing->outstanding_balance ) {
                return 0;
            }*/

            /*if ( $Billing->installment_amount > ( $outstanding_balance - $Billing->outstanding_balance ) ) {
                return $outstanding_balance - $Billing->outstanding_balance;
            }*/

            return $Billing->installment_amount;
        }

        return 0;
    }

    public function getBalanceIfUpdated($date){
        $outstanding_balance = $this->getCurrentBalance();
        $Billing =  $this->Loan->amortizationTables()->where('due_date','<=',$date)
            ->where('outstanding_balance','<=', $outstanding_balance)
            ->orderBy('due_date','desc')->first();

        if ( $Billing ) {
            return $Billing->outstanding_balance;
        }

        return $outstanding_balance;
    }

    public static function getReceivables($billing, $total_overdue){
        $a = array(0,0,0,0,0);

        $i = 0;
        while( true ){
            if( $billing >= $total_overdue ){
                $a[$i] = $total_overdue;
                break;
            } else {

                if( $i == 4 ){
                    $a[$i] = $total_overdue;
                    break;
                } else {
                    $a[$i] = $billing;
                    $total_overdue -= $billing;
                    $i++;
                }
            }
        }

        return $a;
    }

}