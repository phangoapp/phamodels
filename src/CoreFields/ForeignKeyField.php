<?php

namespace PhangoApp\PhaModels\CoreFields;
use PhangoApp\PhaI18n\I18n;

/**
* ForeignKeyfield is a relantioship between two models...
*
*/

/**
* ForeignKeyfield is a relantioship between two models...
*
*/

class ForeignKeyField extends IntegerField{

	//field related in the model...
	public $related_model='';
	public $container_model='';
	public $null_relation=1;
	public $params_loading_mod=array();
	public $default_id=0;
	public $yes_zero=0;
	public $fields_related_model;
	public $name_field_to_field;
	
	function __construct($related_model, $size=11, $null_relation=1, $default=0)
	{

		$this->size=$size;
		$this->form='PhangoApp\PhaModels\CoreForms::SelectForm';
		$this->related_model=&$related_model;
		$this->container_model=$this->related_model->name;
		//Fields obtained from related_model if you make a query...
		$this->fields_related_model=array();
		//Representative field for related model...
		$this->name_field_to_field='';
		$this->null_relation=$null_relation;
		$this->default_id=$default;
		
		//PhangoVar::$model[$related_model]->related_models_delete[]=array('model' => $this->name_model, 'related_field' => $this->name_component);
		
		//echo get_parent_class();

	}
	
	function set_relationships()
	{
		
		//We need the model loaded...
		
		if(isset($this->related_model))
		{
			$this->related_model->related_models_delete[]=array('model' => $this->name_model, 'related_field' => $this->name_component);
		}
		else
		{
		
			//show_error('You need load model before set relantionship', $this->related_model.' model not exists. You need load model before set relantionship with ForeignKeyField with '.$this->name_model.' model', $output_external='');
			
			throw new \Exception($this->related_model.' model not exists. You need load model before set relantionship with ForeignKeyField with '.$this->name_model.' model');
			
			die;
		
		}
	}

	function check($value)
	{
		
		settype($value, "integer");

		//Reload related model if not exists, if exists, only check cache models...

		if(!isset($this->related_model))
		{

			load_model($this->container_model);

		}

		//Need checking if the value exists with a select_count
		
		$num_rows=$this->related_model->select_count('where '.$this->related_model.'.'.$this->related_model->idmodel.'='.$value, $this->related_model->idmodel);
		
		if($num_rows>0)
		{
		
			if($value==0 && $this->yes_zero==0)
			{
				
				return NULL;
			
			}
			
			return $value;

		}
		else
		{
		
			if($this->default_id<=0 && $this->yes_zero==0)
			{
			
				return NULL;
				
			}
			else
			{
			
				return $this->default_id;
			
			}

		}
		

	}
	
	function simple_check($value)
	{
	
		settype($value, 'integer');
		
		return $value;
	
	}
	
	
	function get_type_sql()
	{
	
		$arr_null[0]='NOT NULL';
		$arr_null[1]='NULL';

		return 'INT('.$this->size.') '.$arr_null[$this->null_relation];

	}

	/**
	* This function is used for show the value on a human format
	*/
	
	public function show_formatted($value)
	{
		
		return $this->related_model->components[$this->name_field_to_field]->show_formatted($value);

		//return $value;

	}

	function get_parameters_default()
	{
		
		
		load_libraries(array('forms/selectmodelform'));
		
		//SelectModelForm($name, $class, $value, $model_name, $identifier_field, $where='')
		
		//Prepare parameters for selectmodelform
		
		if(isset($this->name_component) && $this->name_field_to_field!='' && $this->name_model!='' && count(PhangoVar::$model[$this->name_model]->forms)>0)
		{
			PhangoVar::$model[$this->name_model]->forms[$this->name_component]->form='SelectModelForm';
			
			return array($this->name_component, '', '', $this->related_model, $this->name_field_to_field, '');
			
		}
		else
		{
		
			$arr_values=array('', I18n::lang('common', 'any_option_chosen', 'Any option chosen'), '');
			
			return array($this->name_component, '', $arr_values);
			
		}

	}
	
	function get_all_fields()
	{
		
		return array_keys($this->related_model->components);
	
	}

}

?>