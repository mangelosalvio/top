<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    protected $fillable = [
        'journal_desc',
        'journal_code'
    ];

    public function scopeJournalCode($query, $journal_code){
        return $query->whereJournalCode($journal_code)->first();
    }
}
