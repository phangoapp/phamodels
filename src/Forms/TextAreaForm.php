<?php

namespace PhangoApp\PhaModels\Forms;

use PhangoApp\PhaModels\Forms\BaseForm;
use PhangoApp\PhaUtils\Utils;
use PhangoApp\PhaView\View;
use PhangoApp\PhaRouter\Routes;
use PhangoApp\PhaI18n\I18n;

class TextAreaForm extends BaseForm {
    
    public function form()
    {
    
        ?>
        <textarea id="<?php echo $this->name; ?>_field_form" name="<?php echo $this->name; ?>" class="<?php echo $this->css; ?>"><?php echo $this->setform($this->default_value); ?></textarea>
        <?php
    
    }
}

?>
