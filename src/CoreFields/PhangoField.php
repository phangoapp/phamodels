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
use PhangoApp\PhaModels\Forms\BaseForm;
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
    * The model where this component or field live
    */
    
    public $model_instance=false;
	
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
	
	public $form='PhangoApp\PhaModels\Forms\BaseForm';
	
	/**
	* Variable where save a copy of form created from this Field 
	*/
	
	public $form_loaded;
	
	/**
	* Array for create initial extra parameters for form.
	*/
	
	public $parameters=array();
	
	/**
	* A method used for set if this field can be update or insert by everyone. 
	*/
	
	public $protected=false;
	
	/**
	* A property that set the default value
	*/
	
	public $default_value='';
	
	/**
	* A property for know if updated or insert this field
	*/
	
	public $update=0;
	
	/**
	* A property for set if error
	*/
	
	public $error=0;
	
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

		return 'VARCHAR('.$this->size.') NOT NULL DEFAULT ""';

	}
	
	/** 
	* This method is used for return a default value for a form.
	*/

	public function get_parameters_default()
	{

		

	}
	
	/**
	* This method is used for simple checking, used for WhereSql.
	*/
	
	public function simple_check($value)
	{
	
		return $this->check($value);
	
	}
	
	/**
	* Basic check for sql things
	*/
	
	public function check($value)
    {
    
        return str_replace('"', '&quot;', $value);
    
    }
	
	/**
	* Method for create a form, you only need subclass the field if you want another form different to default
	*/
	/*
	public function create_form()
	{
	
        $form=new BaseForm($this->name_component, $this->value);
        $form->default_value=$this->default_value;
        $form->required=$this->required;
        $form->label=$this->label;
        
        return $form;
	
	}*/
	
	
}

?>