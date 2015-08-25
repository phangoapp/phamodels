<?php

namespace PhangoApp\PhaModels\Forms;

use PhangoApp\PhaModels\Forms\BaseForm;
use PhangoApp\PhaModels\CoreFields\PasswordField;

/**
* Basic class for create forms
*/

class PasswordForm extends BaseForm{
    
    public function __construct($name, $value='')
    {
    
        parent::__construct($name, $value);
        
        $this->field=new PasswordField();
    
    }
        
    public function form()
    {
        
        return '<input type="password" class="'.$this->css.'" name="'.$this->name.'" value="">';
    
    }
    
}

?>