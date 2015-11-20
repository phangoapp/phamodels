<?php

namespace PhangoApp\PhaModels\Forms;

use PhangoApp\PhaModels\Forms\BaseForm;
use PhangoApp\PhaUtils\Utils;
use PhangoApp\PhaView\View;
use PhangoApp\PhaRouter\Routes;
use PhangoApp\PhaI18n\I18n;

class NoForm extends BaseForm {
    
    public function form()
    {
    
        return strip_tags($this->default_value);
    
    }
}

?>