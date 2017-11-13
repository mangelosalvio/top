<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AmortizationTable extends Model
{

    public $incrementing = false;

    protected $fillable = [
        'loan_id',
        'term',
        'due_date',
        'installment_amount',
        'rebate_amount',
        'interest_amount',
        'outstanding_balance',
        'outstanding_interest',
        'outstanding_rebate'
    ];

    protected $dates = [
        'due_date'
    ];

    public function loan(){
        return $this->belongsTo(Loan::class);
    }
}
