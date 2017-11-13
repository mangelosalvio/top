<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransType extends Model
{
    public function loans(){
        return $this->hasMany(Loan::class);
    }
}
