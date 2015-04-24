<?php

/**
*
* @author  Antonio de la Rosa <webmaster@web-t-sys.com>
* @file
* @package CoreFields
*
*/

/**
* PhangoField class is the base for make class used on Webmodel::components property.
*
*/

namespace PhangoApp\PhaModels\CoreFields;
use PhangoApp\PhaUtils\Utils;

class PhangoField {

	/**
	* Property used for set this field how indexed in the database table.
	*/

	public $indexed=0;
	
	/**
	* Property used for set this field how unique value in the database table.
	*/

	public $unique=0;
	
	/**
	* The name of the model where this component or field live
	*/
	
	public $name_model='';
	
	/**
	* Name of the field or component.
	*/
	
	public $name_component='';
	
	/**
	* Method used for internal searchs for format the values.
	*
	* 
	*/
	
	/**
	* Required define if this field is required when insert or update a row of this model...
	*/
	
	public $required=0;
	
	/** 
	* $quote_open is used if you need a more flexible sql sentence, 
	* @warning USE THIS FUNCTION IF YOU KNOW WHAT YOU ARE DOING
	*/
	public $quot_open='\'';
	
	/** 
	* $quote_close is used if you need a more flexible sql sentence, 
	* @warning USE THIS PROPERTY IF YOU KNOW WHAT YOU ARE DOING
	*/
	
	public $quot_close='\'';
	
	/**
	* $std_error contain error in field if exists...
	*/
	
	public $std_error='';
	
	/**
	* Label is the name of field
	*/
	public $label="";
	
	/**
	* Value of field...
	*/
	public $value="";
	
	/**
	* Form define the function for use in forms...
	*/
	
	public $form="";
	
	/**
	* Array for create initial parameters for form..
	*/
	
	public $parameters=array();
	
	/**
	* Method used for internal tasks related with searchs. You can overwrite this method in your PhangoField object if you need translate the value that the user want search to a real value into the database.
	*/
	
	function search_field($value)
	{
	
		return Utils::form_text($value);
	
	}
	
	/**
	* Method used for internal tasks related with foreignkeys. By default make nothing.
	*
	* 
	*/
	
	function set_relationships()
	{
	
		
	
	}

	/** 
	* This method is used for describe the new field in a sql language format.
	*/

	public function get_type_sql()
	{

		return 'VARCHAR('.$this->size.') NOT NULL';

	}
	
	/** 
	* This method is used for return a default value for a form.
	*/

	public function get_parameters_default()
	{

		return array($this->name_component, '', '');

	}
	
	/**
	* This method is used for simple checking, used for WhereSql.
	*/
	
	public function simple_check($value)
	{
	
		return $this->check($value);
	
	}
	
	
}

?>