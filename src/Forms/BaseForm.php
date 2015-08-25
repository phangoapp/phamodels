<?php

namespace PhangoApp\PhaModels\Forms;

/**
* Basic class for create forms
*/

class BaseForm {
    
    /**
    * @param string $name The name of form
    * @param string $name The default value of the form
    * @param string $name Field class instance used for check files
    */
    
    public function __construct($name, $value, $field)
    {
        $this->label=name;
        $this->name=name;
        $this->default_value=value;
        $this->css='';
        $this->type='text';
        $this->required=0;
    }
        
    public function form()
    {
        
        return '<input type="'.$this->type.'" class="'.$this->css.'" name="'.$this->setform($this->default_value).'">';
    
    }
    
    #Method for escape value for html input
    
    public function setform(value)
    {
    
        return value.replace('"', '&quot;')
        
    }
}

?>