<?php

namespace PhangoApp\PhaModels\CoreFields;

use PhangoApp\PhaI18n\I18n;

class PercentField extends IntegerField{


    function check($value)
    {
        
        
        settype($value, "integer");

        //Reload related model if not exists, if exists, only check cache models...

        if($value>100 || $value<0)
        {
            
            $this->std_error=I18n::lang('common', 'the_value_can_not_be_greater_than_100', 'The value cannot be greater or minor than 100');

            $this->error=1;
            
            return 0;

        }

        return $value;
        

    }
    
    public function show_formatted($value)
    {
        
        return $value.' %';
        
    }


}

?>
