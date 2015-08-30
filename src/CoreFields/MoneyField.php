<?php

namespace PhangoApp\PhaModels\CoreFields;

class MoneyField extends DoubleField{


    function show_formatted($value)
    {

        return $this->currency_format($value);

    }

    
    static function currency_format($value, $symbol_currency='&euro;')
    {


        return number_format($value, 2).' '.$symbol_currency;

    }

}

?>