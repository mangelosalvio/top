<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loan extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'customer_id',
        'collateral_id',
        'date',
        'unit',
        'plate_no',
        'comaker',
        'trans_type_id',
        'purpose',
        'salesman',
        'dealer',
        'amount',
        'term',
        'interest_rate',
        'rebate_rate',
        'credit_verification',
        'credit_investigator',
        'manager',
        'approving_signatory',
        'res_cert_no',
        'res_cert_date',
        'res_cert_place',
        'date_purchased',

        'is_approved',
        'doc_stamp',
        'notarial_fees',
        'misc_fees',
        'science_stamps',
        'mortgage_fees',
        'legal_and_research_fees',
        'deed_of_assignment_fees',
        'total_rod_charges',
        'transfer_fees',
        'mortgage_and_assignment_fees',
        'misc_lto_fees',
        'total_lto_charges',
        'total_doc_fees',
        'service_fees',
        'od_insurance_fees',
        'net_proceeds',
        'signatories',

        'customer_code',
        'is_balance_forwarded',
        'balance_forwarded',

        'cv_no',
        'check_no',
        'is_renewal',
        'check_date'
    ];

    protected $appends = [
        'loan_no',
        'first_due_term'
    ];

    public static function boot() {
        parent::boot();


        static::saving(function($model){

            foreach ($model->attributes as $key => $value) {
                if ( $key == 'check_date' ) {
                    $model->{$key} = empty($value) ? null : $value;
                }

            }
        });
    }


    public function getFirstDueTermAttribute(){
        if ( isset( $this->attributes['term'] ) ) {
            return $this->amortizationTables()->first()->due_date->toFormattedDateString() . ' ' . $this->attributes['term'] . ' months';
        }
        return null;
    }

    public function amortizationTables(){
        return $this->hasMany(AmortizationTable::class);
    }

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function transType(){
        return $this->belongsTo(TransType::class);
    }

    public function collateral(){
        return $this->belongsTo(Collateral::class);
    }

    public function otherAdditions(){
        return $this->belongsToMany('App\ChartOfAccount','loan_additions','loan_id','chart_of_account_id')->withPivot('amount')->withTimestamps();
    }

    public function otherDeductions(){
        return $this->belongsToMany('App\ChartOfAccount','loan_deductions','LOAN_ID','chart_of_account_id')->withPivot('amount')->withTimestamps();
    }

    public function getLoanNoAttribute(){
        if ( isset( $this->attributes['id'] ) ) {
            return str_pad($this->attributes['id'],7,0,STR_PAD_LEFT);
        }

        return null;
    }

    public function LrAccount(){
        return $this->belongsTo(ChartOfAccount::class, 'lr_account_id');
    }

    public function UiiAccount(){
        return $this->belongsTo(ChartOfAccount::class, 'uii_account_id');
    }

    public function RffAccount(){
        return $this->belongsTo(ChartOfAccount::class, 'rff_account_id');
    }

    public function ArAccount(){
        return $this->belongsTo(ChartOfAccount::class, 'ar_account_id');
    }

    public function loanLedgers(){
        return $this->hasMany(LoanLedger::class);
    }

    public function gl(){
        return GeneralLedger::whereColumnHeaderId($this->attributes['id'])
            ->whereColumnHeader('loans')->first();
    }

}

