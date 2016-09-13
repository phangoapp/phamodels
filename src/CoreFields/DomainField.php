<?php

namespace PhangoApp\PhaModels\CoreFields;

use PhangoApp\PhaModels\CoreFields\CharField;
use PhangoApp\PhaUtils\Utils;

class DomainField extends CharField {

    public function check($value)
    {

        //Delete Javascript tags and simple quotes.
        
        $pattern='/^(([a-zA-Z]{1})|([a-zA-Z]{1}[a-zA-Z]{1})|([a-zA-Z]{1}[0-9]{1})|([0-9]{1}[a-zA-Z]{1})|([a-zA-Z0-9][a-zA-Z0-9-_]{1,61}[a-zA-Z0-9]))\.([a-zA-Z]{2,6}|[a-zA-Z0-9-]{2,30}\.[a-zA-Z]{2,3})$/';
        
        if(preg_match($pattern, $value))
        {
            return Utils::form_text($value);
        }
        else
        {
        
            $this->error=1;
        
            return '';
        
        }

    }

}

?>
