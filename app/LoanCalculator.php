<?php
namespace App;


use Carbon\Carbon;

class LoanCalculator {
    public $amortization_table = [];

    public function __construct($loan_amount, $interest_rate, $terms, $rebate_rate, $date_purchased){
        $this->loan_amount = $loan_amount;
        $this->interest_rate = $interest_rate;
        $this->rebate_rate = $rebate_rate;
        $this->terms = $terms;
        $this->interest_amount = round($this->loan_amount * ($interest_rate / 100),2);
        $this->rebate_amount = round(($this->loan_amount + $this->interest_amount) * ($this->rebate_rate / 100),2);
        $this->pn_amount = $this->loan_amount + $this->rebate_amount + $this->interest_amount;
        $this->date_purchased = new Carbon($date_purchased);

        /**
         * calculate first and succeeding rebate amount
         */

        $this->second_rebate = (int)($this->rebate_amount / $this->terms);
        $this->first_rebate = $this->rebate_amount - ( $this->second_rebate * ($this->terms - 1) );

        /**
         * Calculate first and succeeding installment amount
         */

        $this->second_installment_amount = (int)($this->pn_amount / $this->terms);
        $this->first_installment_amount = $this->pn_amount - ( $this->second_installment_amount * ($this->terms - 1) );

        $this->net_first = $this->first_installment_amount - $this->first_rebate;
        $this->net_second = $this->second_installment_amount - $this->second_rebate;
        $this->net_amount = $this->pn_amount - $this->rebate_amount;

        $this->calculateMonthlyInterest();
    }

    /**
     * @return mixed
     */
    public function getNetAmount()
    {
        return $this->net_amount;
    }

    /**
     * @return mixed
     */
    public function getNetFirst()
    {
        return $this->net_first;
    }

    /**
     * @return int
     */
    public function getNetSecond()
    {
        return $this->net_second;
    }

    /**
     * @return mixed
     */
    public function getFirstInstallmentAmount()
    {
        return $this->first_installment_amount;
    }

    /**
     * @return float
     */
    public function getInterestAmount()
    {
        return $this->interest_amount;
    }

    /**
     * @return float
     */
    public function getFirstRebate()
    {
        return $this->first_rebate;
    }

    /**
     * @return int
     */
    public function getSecondRebate()
    {
        return $this->second_rebate;
    }

    /**
     * @return int
     */
    public function getSecondInstallmentAmount()
    {
        return $this->second_installment_amount;
    }

    /**
     * @return float
     */
    public function getRebateAmount()
    {
        return $this->rebate_amount;
    }

    /**
     * @return mixed
     */
    public function getPnAmount()
    {
        return $this->pn_amount;
    }

    public function getAmortizationTable() {
        return $this->amortization_table;
    }

    private function getRule78Denaminator(){
        $sum = 0;

        for ( $i = 1; $i <= $this->terms ; $i++ ) {
            $sum += $i;
        }

        return $sum;
    }

    private function calculateMonthlyInterest(){
        $monthly_interest = round($this->interest_amount / $this->getRule78Denaminator(),2);
        $outstanding_balance = $this->pn_amount;
        $outstanding_interest = $this->interest_amount;
        $outstanding_rebate = $this->rebate_amount;

        $sum = 0;
        $counter = 1;
        for ( $i = $this->terms ; $i > 1 ; $i-- ) {


            if ( $counter == 1 ) {
                $interest_amount = $this->computeFirstInterest();
                $outstanding_balance -= $this->first_installment_amount;
                $outstanding_interest -= $interest_amount;
                $outstanding_rebate -= $this->first_rebate;

                $this->amortization_table[] = [
                    'installment_amount' => $this->first_installment_amount,
                    'interest_amount' => $interest_amount,
                    'rebate_amount' => $this->first_rebate,
                    'outstanding_balance' => $outstanding_balance,
                    'term' => $counter,
                    'due_date' => $this->getDueDate($this->date_purchased,$counter),
                    'outstanding_interest' => $outstanding_interest,
                    'outstanding_rebate' => $outstanding_rebate
                ];
            } else {
                $interest_amount = floor($monthly_interest * $i);
                $outstanding_balance -= $this->second_installment_amount;
                $outstanding_interest -= $interest_amount;
                $outstanding_rebate -= $this->second_rebate;

                $this->amortization_table[] = [
                    'installment_amount' => $this->second_installment_amount,
                    'interest_amount' => $interest_amount,
                    'rebate_amount' => $this->second_rebate,
                    'outstanding_balance' => $outstanding_balance,
                    'term' => $counter,
                    'due_date' => $this->getDueDate($this->date_purchased,$counter),
                    'outstanding_interest' => $outstanding_interest,
                    'outstanding_rebate' => $outstanding_rebate
                ];
            }

            $sum += $interest_amount;
            $counter++;
        }

        $outstanding_balance -= $this->second_installment_amount;
        $outstanding_interest -= ($this->interest_amount - $sum);
        $outstanding_rebate -= $this->second_rebate;

        $this->amortization_table[] = [
            'installment_amount' => $this->second_installment_amount,
            'interest_amount' => $this->interest_amount - $sum,
            'rebate_amount' => $this->second_rebate,
            'outstanding_balance' => $outstanding_balance,
            'term' => $counter,
            'due_date' => $this->getDueDate($this->date_purchased,$counter),
            'outstanding_interest' => $outstanding_interest,
            'outstanding_rebate' => $outstanding_rebate
        ];
    }

    private function computeFirstInterest(){

        $total_interest = 0;
        for ( $i = 1 ; $i < $this->terms ; $i++ ) {
            $total_interest += floor(round($this->interest_amount / $this->getRule78Denaminator(),2) * $i);
        }

        return $this->interest_amount - $total_interest;
    }

    private function getDueDate(Carbon $due_date, $term){
        if ( $due_date->copy()->day(1)->addMonth($term)->daysInMonth <= $due_date->day ) {
            return $due_date->copy()->day(1)->month( $due_date->month + $term )->day($due_date->copy()->day(1)->month($due_date->month + $term)->daysInMonth)->toDateString();
        } else {
            return $due_date->copy()->addMonth($term)->toDateString();
        }
    }



}