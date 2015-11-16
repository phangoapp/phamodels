<?php

namespace PhangoApp\PhaModels\CoreFields;

use PhangoApp\PhaModels\CoreFields\CharField;
use PhangoApp\PhaUtils\Utils;

class IpField extends CharField {

    public function check($value)
    {

        //Delete Javascript tags and simple quotes.
        
        if(filter_var($value, FILTER_VALIDATE_IP))
        {
            return $value;
        }
        else
        {
        
            return false;
        
        }

    }

}

?>