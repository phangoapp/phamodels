<?php

namespace PhangoApp\PhaModels\Forms;

use PhangoApp\PhaModels\Forms\BaseForm;
use PhangoApp\PhaI18n\I18n;

/**
* Basic class for create forms
*/

class MoneyForm extends BaseForm{
    
    public function __construct($name, $value, $extra_parameters=array())
    {
        
        parent::__construct($name, $value, $extra_parameters);
        
        $this->comment_form='€';
        
    }
    
    public function form()
    {
    
        $value=$this->default_value/100;
    
        return '<input type="'.$this->type.'" id="'.$this->name.'_field_form" class="'.$this->css.'" name="'.$this->name.'" value="'.$this->setform($value).'" '.$this->extra_param.'> '.$this->comment_form;
    
    }
        
    
}

?>
