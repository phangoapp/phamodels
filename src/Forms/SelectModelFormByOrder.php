<?php

namespace PhangoApp\PhaModels\Forms;

use PhangoApp\PhaModels\Webmodel;
use PhangoApp\PhaModels\Forms\SelectForm;
use PhangoApp\PhaI18n\I18n;

class SelectModelFormByOrder extends SelectForm {

    public $model_name;
    public $identifier_field;
    public $field_parent;

    public function __construct($name, $value, $model, $identifier_field, $field_parent, $where=['WHERE 1=1', []], $null_yes=1)
    {
    
        parent::__construct($name, $value);
        
        $this->model=$model;
        
        $this->identifier_field=$identifier_field;
        
        $this->field_parent=$field_parent;
        
        $this->where=$where;
        
        $this->null_yes=$null_yes;
    
    }
    
    public function form()
    {

        //Need here same thing that selectmodelform...
        
        $arr_model=array($this->default_value);
        
        if($this->null_yes==1)
        {
        
            $this->arr_select[0]=I18n::lang('common', 'no_element_chosen', 'No element chosen');
        
        }
        
        $arr_elements=array();
        
        $query=$this->model->select([$this->model->idmodel, $this->identifier_field, $this->field_parent]);
        
        while($arr_field=$this->model->fetch_array($query))
        {
            
            $idparent=$arr_field[$this->field_parent];

            $element_model=$this->model->components[$this->identifier_field]->show_formatted($arr_field[ $this->identifier_field ]);

            $arr_elements[$idparent][]=array($element_model, $arr_field[ $this->model->idmodel ]);

        }
        
        $this->arr_select=$this->recursive_list_select($arr_elements, 0, $this->arr_select, '');
        

        return parent::form();

    }

    public function recursive_list_select($arr_elements, $element_id, $arr_result, $separator)
    {

        $separator.=$separator;
        
        if(isset($arr_elements[$element_id]))
        {

            foreach($arr_elements[$element_id] as $element)
            {
                
                $arr_result[$element[1]]=$separator.$element[0];
                //$arr_result[]=$element[1];
                
                if( isset($arr_elements[$element[1]] ) )
                {

                    $arr_result=$this->recursive_list_select($arr_elements, $element[1], $arr_result, $separator.'--');

                }

            }

        }

        return $arr_result;

    }

}

?>