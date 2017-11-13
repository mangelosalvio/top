<?php

namespace App;

use Carbon\Carbon;
use Collective\Html\Eloquent\FormAccessible;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes, FormAccessible;
    protected $guarded = [
        'id',
        'customer_code',
        'date_entered',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $appends = [
        'name'
    ];

    public function formCreatedAtAttribute($value){
        return Carbon::parse($value)->format("Y-m-d");
    }

    public function getNameAttribute(){
        return $this->last_name . ', ' . $this->first_name . ' ' . $this->middle_name;
    }

    public function collaterals(){
        return $this->hasMany(Collateral::class);
    }
}
