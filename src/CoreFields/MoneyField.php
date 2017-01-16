<?php

namespace PhangoApp\PhaModels\CoreFields;

//Work with cents.

class MoneyField extends DoubleField{

    static public $dec_point=',';
    static public $thousands_sep='.';

    public function __construct()
    {
        
        parent::__construct(11, true, 0, 0); 
        $this->form='PhangoApp\PhaModels\Forms\MoneyForm';
        
    }
    
    function check($value)
    {
        $value=$value*100;
        
        return parent::check($value);
        
    }


    function show_formatted($value)
    {

        return $this->currency_format($value);

    }

    
    static function currency_format($value, $symbol_currency='&euro;')
    {

        $value=$value/100;

        return number_format($value, 2, MoneyField::$dec_point, MoneyField::$thousands_sep).' '.$symbol_currency;

    }

}

?>
