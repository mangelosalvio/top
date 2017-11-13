<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CollectionPenalty extends Model
{
    protected $fillable = [
        'collection_id',
        'due_date',
        'days_delayed',
        'arrears',
        'penalty'
    ];
}
