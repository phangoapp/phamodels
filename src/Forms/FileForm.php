<?php

namespace PhangoApp\PhaModels\Forms;

use PhangoApp\PhaModels\Forms\BaseForm;
use PhangoApp\PhaUtils\Utils;

/**
* Basic class for create forms
*/

class FileForm extends BaseForm{
    
    public function __construct($name, $value='')
    {
    
        parent::__construct($name, $value);
        
        $this->type='file';
        $this->enctype=1;
        $this->file_url='';
    
    }
    
    public function form()
    {
        
        return '<input type="'.$this->type.'" id="'.$this->name.'_field_form" class="'.$this->css.'" name="'.$this->name.'_file" value=""> <a href="'.$this->file_url.'/'.$this->default_value.'">'.Utils::form_text($this->default_value).'</a><input type="hidden" name="'.$this->name.'" value="'.$this->setform($this->default_value).'" />';
    
    }
    
        
    
}

?>
