<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Collection extends Model
{

    use SoftDeletes;
    public static function boot(){
        parent::boot();

        $arr_numbers = [
            'principal_amount',
            'rff_debit',
            'rff_credit',
            'uii_debit',
            'interest_income_credit',
            'total_amount_due',
        ];

        static::saving(function($model) use ($arr_numbers){
            foreach ($model->attributes as $key => $value) {

                if ( in_array($key, $arr_numbers) ) {
                    $model->{$key} = $value == "" ? 0 : $value;
                } else {
                    $model->{$key} = $value == "" ? null : $value;
                }
            }
        });

    }


    protected $fillable = [
      'account_code',
      'loan_id',
      'current_balance',
      'uii_balance',
      'rff_balance',
      'ar_balance',

      'principal_amount',
      'rff_debit',
      'rff_credit',
      'uii_debit',
      'interest_income_credit',
      'total_amount_due',

      'or_no',
      'or_date',
      'cash_amount',
      'check_amount',
      'total_payment_amount',
      'bank_branch',
      'check_no',
      'check_date',

      'is_penalty_computed',
      'last_transaction_date',
      'penalty_rate',
      'penalty_as_of_date',
      'days_allowance',

      'total_penalty',
      'penalty_disc_rate',
      'penalty_disc_amount',
      'net_penalty_due',

      'received_from'
    ];

    public function loan(){
        return $this->belongsTo(Loan::class);
    }

    public function lessAccounts(){
        return $this->belongsToMany(ChartOfAccount::class,'collection_less_accounts','collection_id','account_id')->withPivot([
            'amount'
        ]);
    }

    public function additionalAccounts(){
        return $this->belongsToMany(ChartOfAccount::class,'collection_add_accounts','collection_id','account_id')->withPivot([
            'amount'
        ]);
    }

    public function penalties() {
        return $this->hasMany(CollectionPenalty::class);
    }

    public function ledger(){
        return $this->hasOne(LoanLedger::class);
    }

    public function gl(){
        return GeneralLedger::whereColumnHeaderId($this->attributes['id'])
            ->whereColumnHeader('cash_receipts')->first();
    }
}
