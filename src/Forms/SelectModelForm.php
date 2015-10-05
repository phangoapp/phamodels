<?php

namespace PhangoApp\PhaModels\Forms;

use PhangoApp\PhaModels\Forms\SelectForm;
use PhangoApp\PhaModels\Webmodel;

/**
* Basic class for create forms
*/

class SelectModelForm extends SelectForm{

    public $model;
    
    public $conditions='WHERE 1=1';
    
    public $field_value='';
    
    public $field_name='';
    
    public $raw_query=0;
        
    public function form()
    {
    
        if($this->field_value=='' || $this->field_name=='')
        {
        
            throw new \Exception('Need field_value and field_name property');
        
        }
    
        
        $this->model->set_conditions($this->conditions);
        
        $query=$this->model->select(array($this->field_name, $this->field_value), $this->raw_query);
        
        while($row=$this->model->fetch_array($query))
        {
            
            $this->arr_select[$row[$this->field_value]]=$row[$this->field_name];
            
        
        }
        
        return parent::form();
        
        /*
        
        $arr_selected[$this->default_value]=' selected';
        
        ob_start();
        
        ?>
        <select name="<?php echo $this->name; ?>">
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
        
        return $input;*/
    
    }
    
}

?>