<?php

/**
*
* @author  Antonio de la Rosa <webmaster@web-t-sys.com>
* @file
* @package ExtraFields
*
*/

namespace PhangoApp\PhaModels\CoreFields;

class NormalizeField extends TextField {

	
	public $form='HiddenForm';
	
	public function check($value)
	{
		
		$value=$this->check_text($value);
		
		if($value=='')
		{
		
            $this->error=1;
		
		}
		
		return $value;
		

	}
	
	function search_field($value)
	{
	
		return $this->check_text($value);
	
	}
	
	static public function check_text($value, $separator='-')
	{
	
		$str_normalize=\PhangoApp\PhaUtils\Utils::slugify(strip_tags($value));
		
		$arr_normalize=explode($separator, $str_normalize);
		
		sort($arr_normalize);
		
		$value=implode('%', $arr_normalize);
		
		return $value;
	
	}

}

?>
