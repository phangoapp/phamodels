<?php

namespace PhangoApp\PhaModels\Forms;
use PhangoApp\PhaModels\Forms\DateForm;
use PhangoApp\PhaModels\CoreFields\DateTimeField;

class DateTimeForm {

    public $set_time=1;

    function form()
    {

        $timestamp=DateTimeField::obtain_timestamp_datefield($this->default_value);
        // return '<input type="'.$this->type.'" class="'.$this->css.'" name="'.$this->name.'" value="'.$this->setform($this->default_value).'">';
        
        return DateForm($this->name, $class, $this->default_value, $this->set_time);

    }
   
}

?>