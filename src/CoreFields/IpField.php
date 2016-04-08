<?php

namespace PhangoApp\PhaModels\CoreFields;

use PhangoApp\PhaModels\CoreFields\CharField;
use PhangoApp\PhaUtils\Utils;
use PhangoApp\PhaI18n\I18n;

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
            $this->std_error=I18n::lang('common', 'no_valid_ip', 'This ip is not valid');
            $this->error=1;
        
            return "";
        
        }

    }

}

?>
