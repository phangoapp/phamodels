<?php

namespace PhangoApp\PhaModels\Forms;

use PhangoApp\PhaModels\Forms\BaseForm;

/**
* Basic class for create forms
*/

class FileForm extends BaseForm{
    
    public function __construct($name, $value='')
    {
    
        parent::__construct($name, $value);
        
        $this->type='file';
        $this->enctype=1;
    
    }
        
    
}

?>