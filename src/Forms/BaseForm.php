<?php

namespace PhangoApp\PhaModels\Forms;

use PhangoApp\PhaModels\CoreFields\CharField;
use PhangoApp\PhaI18n\I18n;

/**
* Basic class for create forms
*/

class BaseForm {
    
    /**
    * @param string $name The name of form
    * @param string $name The default value of the form
    * @param string $name Field class instance used for check files
    */
    
    public $std_error='';
    
    /**
    * A property for add enctype for a form where this field is used
    */
    
    public $enctype=0;
    
    public function __construct($name, $value, $extra_parameters=array())
    {
        $this->label=$name;
        $this->name=$name;
        $this->default_value=$value;
        $this->css='';
        $this->type='text';
        $this->required=0;
        $this->field=new CharField();
        $this->comment_form='';
        $this->txt_error = I18n::lang('common', 'error_in_field', 'Error in field');
        $this->extra_param='';
    }
        
    public function form()
    {
        
        return '<input type="'.$this->type.'" id="'.$this->name.'_field_form" class="'.$this->css.'" name="'.$this->name.'" value="'.$this->setform($this->default_value).'" '.$this->extra_param.'> '.$this->comment_form;
    
    }
    
    public function set_parameters(array $parameters)
    {
    
        foreach($parameters as $property => $value)
        {
        
            $this->$property=$value;
        
        }
    
    }
    
    /**
    * Static method where is registered the js necessary for a field
    */
    
    static public function js()
    {
    
    }
    
    /**
    * Static method where is registered the js necessary for a field
    */
    
    static public function css()
    {
    
    }
    
    /**
    * Static method where is registered the js necessary for a field
    */
    
    static public function header()
    {
    
    }
    
    /**
    * Method for escape value for html input
    */
    
    public function setform($value)
    {
    
        return str_replace('"', '&quot;', $value);
        
    }
}

?>
