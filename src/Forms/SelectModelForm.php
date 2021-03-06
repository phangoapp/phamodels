<?php

namespace PhangoApp\PhaModels\Forms;

use PhangoApp\PhaModels\Forms\SelectForm;
use PhangoApp\PhaModels\Webmodel;

/**
* Basic class for create forms from a model
*/

class SelectModelForm extends SelectForm{

    public $model;
    
    public $conditions=['WHERE 1=1', []];
    
    public $field_value='';
    
    public $field_name='';
    
    public $raw_query=0;
    
    public $empty_value=true;
    
    public function __construct($name, $value, $model, $field_name, $field_value)
    {
    
        parent::__construct($name, $value);
        
        $this->model=$model;
        
        $this->field_name=$field_name;
        
        $this->field_value=$field_value;
    
    }
        
    public function form()
    {
    
        if($this->field_value=='' || $this->field_name=='')
        {
        
            throw new \Exception('Need field_value and field_name property');
        
        }
    
        
        $this->model->set_conditions($this->conditions);
        
        $query=$this->model->select(array($this->field_name, $this->field_value), $this->raw_query);
        
        if($this->empty_value)
        {
        
            $this->arr_select['']='';
            
        }
        
        while($row=$this->model->fetch_array($query))
        {
            
            $this->arr_select[$row[$this->field_value]]=$this->model->components[$this->field_name]->show_formatted($row[$this->field_name]);
            
        
        }
        
        return parent::form();
        
    
    }
    
}

?>
