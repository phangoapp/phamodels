<?php

namespace PhangoApp\PhaModels\Forms;

use PhangoApp\PhaModels\Forms\BaseForm;

/**
* Basic class for create forms
*/

class SelectForm extends BaseForm{

    public $arr_select=[];
    
    public function __construct($name, $value, $arr_select=[])
    {
        
        parent::__construct($name, $value);
        
        $this->arr_select=$arr_select;
        
    }
        
    public function form()
    {
        
        //return '<input type="password" class="'.$this->css.'" name="'.$this->name.'" value="">';
        $arr_selected[$this->default_value]=' selected';
        
        ob_start();
        
        ?>
        <select name="<?php echo $this->name; ?>" id="<?php echo $this->name; ?>_field_form">
            <?php
            
            foreach($this->arr_select as $value => $select)
            {
                
                settype($arr_selected[$value], 'string');
            
                ?>
                <option value="<?php echo $value; ?>"<?php echo $arr_selected[$value]; ?>><?php echo $select; ?></option>
                <?php
            
            }
            
            ?>
        </select>
        <?php
        
        $input=ob_get_contents();
        
        ob_end_clean();
        
        return $input;
    
    }
    
}

?>
