<?php

namespace PhangoApp\PhaModels\CoreFields;
use PhangoApp\PhaUtils\Utils;
use PhangoApp\PhaModels\Webmodel;

/**
* Serializefield is a field if you need save serialize values
*/

class SerializeField extends PhangoField {

	public $value="";
	public $label="";
	public $required=0;
	public $form='PhangoApp\PhaModels\Forms\BaseForm';
	public $quot_open='\'';
	public $quot_close='\'';
	public $std_error='';
	public $related_type='';
	public $callback_values='PhangoApp\PhaModels\CoreFields\SerializeField::set_format';
	
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
		
		return Webmodel::escape_string(serialize($value));

	}

	function get_type_sql()
	{

		return 'TEXT NOT NULL DEFAULT ""';
		

	}
	
	/**
	* This function is used for show the value on a human format
	*/

	public function show_formatted($value)
	{
		
		return $this->callback_values($value);

	}
	
	/**
	* Method for choose the callback method for format the value
    */
    
    static public function set_format($serialize_value)
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