<?php

namespace PhangoApp\PhaModels\CoreFields;

/**
*
*/

class ArrayField extends SerializeField {

	/**
	* This function is used for show the value on a human format
	*/

	public function show_formatted($value, $key_value='')
	{
	
		$real_value=unserialize($value);
	
		if($key_value==='')
		{
			
			return implode(', ', $return_value);
			
		}
		else
		if(isset($real_value[$key_value]))
		{
		
			return $real_value[$key_value];
		
		}

	}

}

?>