<?php

namespace PhangoApp\PhaModels\CoreFields;
use PhangoApp\PhaUtils\Utils;
use PhangoApp\PhaI18n\I18n;
use PhangoApp\PhaModels\Forms\SelectForm;

/**
*
*/

class ChoiceField extends PhangoField {

	public $size=11;
	public $value=0;
	public $label="";
	public $required=0;
	public $quot_open='\'';
	public $quot_close='\'';
	public $std_error='';
	public $type='integer';
	public $arr_values=array();
	public $arr_formatted=array();
	public $default_value='';
    public $form='PhangoApp\PhaModels\Forms\SelectForm';
	

	function __construct($size=11, $type='integer', $arr_values=array(), $default_value='')
	{

		$this->size=$size;
		$this->type=$type;
		$this->arr_values=$arr_values;
		$this->default_value=$default_value;
		$this->arr_formatted['']=I18n::lang('common', 'none_selected', 'None selected');
		
		$parameters=[];
        
		foreach($arr_values as $value)
		{
			
			$this->arr_formatted[$value]=$value;
            $parameters[$value]=$value;
		
		}
        
        $this->parameters=[$parameters];
	
	}
	
	function restart_formatted()
	{
	
		foreach($this->arr_values as $value)
		{
			
			$this->arr_formatted[$value]=$value;
		
		}
	
	}

	function check($value)
	{
		
		switch($this->type)
		{
		
			case 'integer':

				settype($value, "integer");

			break;

			case 'string':

				$value=Utils::form_text($value);

			break;

		}
		
		if(in_array($value, $this->arr_values))
		{	
			
			return $value;

		}
		else
		{
            $this->error=1;
			return $this->default_value;

		}

	}

	function get_type_sql()
	{

		switch($this->type)
		{
		
			case 'integer':

			return 'INT('.$this->size.') NOT NULL DEFAULT "0"';
			
			break;

			case 'string':

			return 'VARCHAR('.$this->size.') NOT NULL DEFAULT ""';

			break;

		 }	

	}
	
	/**
	* This function is used for show the value on a human format
	*/

	public function show_formatted($value)
	{
	
        //$this->restart_formatted();
		
		return $this->arr_formatted[$value];

	}
    /*
	function get_parameters_default()
	{	

		if(count($this->arr_values)>0)
		{
			$arr_return=array($this->default_value);

			foreach($this->arr_values as $value)
			{

				$arr_return[]=$this->arr_formatted[$value];
				$arr_return[]=$value;

			}

			$arr_values=$arr_return;

		}
		else
		{

			$arr_values=array(0 => 'Option 1', 1 => 'Option 2');

		}
		
		return array($this->name_component, '', $arr_values);

	}*/
	/*
	public function create_form()
    {
    
        $form=new SelectForm($this->name_component, $this->value);
        $form->default_value=$this->default_value;
        $form->required=$this->required;
        $form->label=$this->label;
        $form->arr_select=$this->arr_formatted;
        
        return $form;
    
    }*/

}

?>
