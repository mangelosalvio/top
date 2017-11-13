<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeneralLedger extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'date',
        'journal_id',
        'particulars',
        'reference',
        'column_header',
        'column_header_id'
    ];

    public function journal(){
        return $this->belongsTo(Journal::class);
    }

    public function chartOfAccounts(){
        return $this->belongsToMany(ChartOfAccount::class)->withPivot([
            'debit',
            'credit',
            'description'
        ]);
    }
}
