<?php

namespace PhangoApp\PhaModels\CoreFields;

use PhangoApp\PhaModels\CoreFields\CharField;
use PhangoApp\PhaUtils\Utils;

class UrlField extends CharField {

    public function check($value)
    {

        //Delete Javascript tags and simple quotes.
        
        if(filter_var($value, FILTER_VALIDATE_URL))
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