<?php

namespace PhangoApp\PhaModels\CoreFields;
use PhangoApp\PhaUtils\Utils;

/**
* Serializefield is a field if you need save serialize values
*/

class SerializeField extends PhangoField {

	public $value="";
	public $label="";
	public $required=0;
	public $form="TextForm";
	public $quot_open='\'';
	public $quot_close='\'';
	public $std_error='';
	public $related_type='';
	
	//type_data can be any field type that is loaded IntegerField, etc..
	
	function __construct($related_type)
	{
		
		$this->related_type=&$related_type;
		
	}
	
	public $type_data='';

	//This method is used for check all members from serialize

	function recursive_form($value)
	{

		if(gettype($value)=="array")
		{

			foreach($value as $key => $value_key)
			{

				if(gettype($value_key)=="array")
				{

					$value[$key]=$this->recursive_form($value_key);

				}
				else
				{

					//Create new type.
					//$type_field=new $this->related_type();
				
					$value[$key]=$this->related_type->check($value_key);

				}

			}

		}

		return $value;

	}

	function check($value)
	{
		
		$value=$this->recursive_form($value);

		$this->value=$value;
		
		return webtsys_escape_string(serialize($value));

	}

	function get_type_sql()
	{

		return 'TEXT NOT NULL';
		

	}
	
	/**
	* This function is used for show the value on a human format
	*/

	public function show_formatted($value)
	{

		$real_value=unserialize($value);
		
		return implode(', ', $return_value);

	}
	
	static function unserialize($value)
	{

		$real_value=@unserialize($value);
		
		if($real_value!==false)
		{
			return $real_value;
		}
		else
		{
		
			//$this->std_error='';
			return false;
		
		}

	}
	
}

?>