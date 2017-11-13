<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoanLedger extends Model
{
    protected $fillable = [
        'date',
        'collection_id',
        'loan_id',
        'payment_amount',
        'rebate_amount',
        'outstanding_balance',
        'outstanding_interest',
        'outstanding_rebate'
    ];

    public function collection(){
        return $this->belongsTo(Collection::class);
    }
}
