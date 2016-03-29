<?php

namespace PhangoApp\PhaModels\CoreFields;
use PhangoApp\PhaI18n\I18n;

/**
*
*/

class ParentField extends IntegerField{

	//field related in the model...
	public $parent_model='';

	function __construct($size=11, $parent_model, $name_field='', $name_value='')
	{

		$this->parent_model=$parent_model;
		$this->size=$size;
		$this->form='PhangoApp\PhaModels\Forms\SelectModelForm';
		$this->parameters=array($this->parent_model, $name_field, $name_value);

	}

	function check($value)
	{
		
		settype($value, "integer");

		//Check model
		
		//$old_conditions=$this->parent_model->conditions;
		
		$this->parent_model->conditions='where '.$this->parent_model->idmodel.'='.$value;
		
		$num_rows=$this->parent_model->select_count();
		
		//$this->parent_model->conditions=$old_conditions;
		
		if($num_rows>0)
		{

			return $value;

		}
		else
		{
			$this->error=1;
			return 0;

		}
		

	}
	
	/**
	* This function is used for show the value on a human format
	*/

	public function show_formatted($value)
	{

		return $value;

	}

	function get_parameters_default()
	{
		

		$arr_values=array('', I18n::lang('common', 'any_option_chosen', 'Any option chosen'), '');
		
		return array($this->name_component, '', $arr_values);

	}
	
	public function process_update_field($class, $name_field, $conditions, $value)
	{
	
        $class->conditions=$conditions.' and '.$class->idmodel.'='.$value;
    
		$num_rows=$class->select_count();
		
		if($num_rows==0)
		{
		
			return true;
		
		}
		else
		{
		
			return false;
		
		}
	
	}
	
	public function obtain_parent_tree($id, $field_ident, $url_op)
	{
		
		$arr_parent=array();
		$arr_link_parent=array();
		
        $this->parent_model->conditions='';
        
		$query=$this->parent_model->select(array( $this->parent_model->idmodel, $this->name_component, $field_ident) );
		
		while(list($id_block, $parent, $name)=webtsys_fetch_row($query))
		{
		
			$arr_parent[$id_block]=array($parent, $name);
		
		}
		
		$arr_link_parent=$this->obtain_recursive_parent($id, $arr_parent, $arr_link_parent, $field_ident, $url_op);
		
		$arr_link_parent=array_reverse($arr_link_parent, true);
		
		return $arr_link_parent;
	
	}
	
	public function obtain_recursive_parent($id, $arr_parent, $arr_link_parent, $field_ident, $url_op)
	{
	
		//$arr_link_parent[]=array('nombre', 'enlace');
		
		//$arr_link_parent=array();
		
		if($id>0)
		{
			
			$arr_link_parent[$id]=array($this->parent_model->components[$field_ident]->show_formatted($arr_parent[$id][1]), add_extra_fancy_url($url_op, array($this->name_component => $id) ) );
			
			if($arr_parent[$id][0]>0)
			{
			
				$arr_link_parent=$this->obtain_recursive_parent($arr_parent[$id][0], $arr_parent, $arr_link_parent, $field_ident, $url_op);
		
			}
		
		}
	
		return $arr_link_parent;
	}

}

?>
