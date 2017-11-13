<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChartOfAccount extends Model
{
    protected $fillable = [
      'account_code',
      'account_desc',
      'account_type_id',
      'main_account_id',
      'is_bank_account'
    ];

    protected $appends = [
        'label'
    ];

    public function accountType(){
        return $this->belongsTo(AccountType::class);
    }

    public function scopeType($query, $type){
        $AccountType = AccountType::whereAccountTypeCode($type)->first();
        return $query->whereAccountTypeId($AccountType->id);
    }

    public function loanAdditions(){
        return $this->belongsToMany('App\Loan','loan_additions','chart_of_account_id','loan_id')->withPivot('amount')->withTimestamps();
    }

    public function getLabelAttribute(){
        return "{$this->account_code} - {$this->account_desc}";
    }

    public function loan(){
        return $this->hasOne(Loan::class,'lr_account_id');
    }

    public function scopeAccountCode($query, $account_code){
        return $query->whereAccountCode($account_code)->first();
    }

    public function scopeBankAccounts($query){
        return $query->whereIsBankAccount(1);
    }


}
