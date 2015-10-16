<?php

namespace PhangoApp\PhaModels\Forms;
use PhangoApp\PhaModels\Forms\DateForm;
use PhangoApp\PhaModels\CoreFields\DateTimeField;

class DateTimeForm extends DateForm {

    function form()
    {

        $this->default_value=DateTimeField::obtain_timestamp_datefield($this->default_value);
        
        return parent::form();

    }
   
}

?>