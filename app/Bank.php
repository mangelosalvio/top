<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $fillable = [
      'bank_desc',
      'chart_of_account_id'
    ];

    public function account(){
        return $this->belongsTo(ChartOfAccount::class,'chart_of_account_id');
    }
}
