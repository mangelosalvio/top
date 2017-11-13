<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Collateral extends Model
{

    use SoftDeletes;

    public static function boot(){
        parent::boot();

        static::saving(function($model){
            foreach ($model->attributes as $key => $value) {
                $model->{$key} = empty($value) ? null : $value;
            }
        });
    }

    protected $guarded = [
        'id'
    ];

    protected $appends = [
        'label',
        'collateral_desc'
    ];

    public function collateralClass(){
        return $this->belongsTo(CollateralClass::class);
    }

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function getLabelAttribute(){
        if ( $this->collateralClass ) {
            if ( $this->attributes['collateral_type'] == "CHATTEL" ) {
                return $this->collateralClass->class_desc . ' | ' . $this->make;
            } else {
                return $this->collateralClass->class_desc . ' | Title No.' . $this->title_no;
            }

        }

        return null;
    }

    public function getCollateralDescAttribute(){
        if ( $this->attributes['collateral_type'] == "CHATTEL" ) {
            return "1 UNIT OF " . "{$this->attributes['make']} {$this->attributes['type']} {$this->attributes['model']}";
        } else {
            return "TITLE NO: " . $this->attributes['title_no'] . "; " .
                "LOT NO: " . $this->attributes['lot_no'] ."; " .
                "AREA: " . $this->attributes['area'];
        }
    }

    public function loan(){
        return $this->hasOne(Loan::class);
    }

}
