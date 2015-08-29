<?php

namespace PhangoApp\PhaModels\Forms;

use PhangoApp\PhaModels\Forms\BaseForm;

/**
* Basic class for create forms
*/

class HiddenForm extends BaseForm{
    
    public function __construct($name, $value='')
    {
    
        parent::__construct($name, $value);
        
        $this->type='hidden';
    
    }
        
    
}

?>