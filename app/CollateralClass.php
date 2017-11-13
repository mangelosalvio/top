<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CollateralClass extends Model
{
    protected $fillable = [
        'class_code',
        'class_desc'
    ];

    public function collaterals(){
        return $this->hasMany(Collateral::class);
    }
}
