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
        <p><textarea class="tinymce_editor" name="<?php echo $this->name; ?>"><?php echo $this->setform($this->default_value); ?></textarea></p>
        <?php
    
    }
}

?>