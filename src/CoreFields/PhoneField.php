<?php

/**
*
* @author  Antonio de la Rosa <webmaster@web-t-sys.com>
* @file
* @package ExtraFields
*
*/

namespace PhangoApp\PhaModels\CoreFields;

class PhoneField extends CharField{


	public function check($value)
	{
		
		if(!preg_match('/^[0-9]+$/', $value))
		{
			$this->error=1;
			
			return '';
		
		}

		return $value;
		

	}


}

?>