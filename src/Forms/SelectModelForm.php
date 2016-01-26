<?php

namespace PhangoApp\PhaModels\Forms;

use PhangoApp\PhaModels\Forms\SelectForm;
use PhangoApp\PhaModels\Webmodel;

/**
* Basic class for create forms
*/

class SelectModelForm extends SelectForm{

    public $model;
    
    public $conditions=['WHERE 1=1', []];
    
    public $field_value='';
    
    public $field_name='';
    
    public $raw_query=0;
    
    public function __construct($name, $value, $model, $field_name, $field_value)
    {
    
        parent::__construct($name, $value);
        
        $this->model=&$model;
        
        $this->field_name=$field_name;
        
        $this->field_value=$field_value;
    
    }
        
    public function form()
    {
    
        if($this->field_value=='' || $this->field_name=='')
        {
        
            throw new \Exception('Need field_value and field_name property');
        
        }
    
        
        $this->model->set_conditions($this->conditions[0], $this->conditions[1]);
        
        $query=$this->model->select(array($this->field_name, $this->field_value), $this->raw_query);
        
        while($row=$this->model->fetch_array($query))
        {
            
            $this->arr_select[$row[$this->field_value]]=$row[$this->field_name];
            
        
        }
        
        return parent::form();
        
    
    }
    
}

?>