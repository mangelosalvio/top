<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CheckVoucherDetail extends Model
{
    protected $fillable = [
        'chart_of_account_id',
        'debit',
        'credit',
        'check_voucher_id'
    ];

    public static function boot(){
        parent::boot();

        static::saving(function($model){
            foreach ($model->attributes as $key => $value) {
                if ( in_array($key, ['debit','credit']) ) {
                    $model->{$key} = empty($value) ? 0 : $value;
                }
            }
        });
    }

    public function checkVoucher(){
        return $this->belongsTo(CheckVoucher::class);
    }

    public function account(){
        return $this->belongsTo(ChartOfAccount::class,'chart_of_account_id');
    }


}
