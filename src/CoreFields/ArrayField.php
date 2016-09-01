<?php

namespace PhangoApp\PhaModels\CoreFields;

/**
*
* Class for create json strings with values
* 
*/

class ArrayField extends SerializeField {
    
    public $key_value='';

    public function __construct($related_type, $key_value='')
    {
        
        parent::__construct($related_type);
        
        $this->key_value=$key_value;
        
    }

	/**
	* This function is used for show the value on a human format
	*/

	public function show_formatted($value, $key_value='')
	{
	
		$real_value=json_decode($value, true);
	
		if($key_value==='')
		{
			
			return implode(', ', $return_value);
			
		}
		else
		if(isset($real_value[$this->key_value]))
		{
		
			return $real_value[$this->key_value];
		
		}

	}

}

?>
