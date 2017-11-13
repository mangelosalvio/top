<?php namespace App\Library;

class NumToWords {

    public function __construct($value){

        $this->value = $value;
        $test = (int) $this->value;
        $decimal = (int)ceil(($this->value - $test) * 100);
        $this->f = new \NumberFormatter(locale_get_default(), \NumberFormatter::SPELLOUT);
        if ( $decimal > 0 ) {
            $this->word = "***".str_replace(" ","*",strtoupper($this->f->format($test)))."*PESOS*&*".$decimal."/100***";
        } else {
            $this->word = "***".str_replace(" ","*",strtoupper($this->f->format($test)))."*PESOS*ONLY***";
        }
    }

    public function getWord(){
        return $this->word;
    }

    public function getPlainWord(){
        return strtoupper($this->f->format($this->value));
    }

}