<?php

namespace PhangoApp\PhaModels\CoreFields;
use PhangoApp\PhaUtils\Utils;

/** 
* Datefield is a field for save dates in timestamp, this value is a timestamp and you need use form_date or form_time for format DateField
*/

class DateField extends PhangoField {

	public $size=11;	
	public $value="";	
	public $required=0;
	public $form="";
	public $label="";
	public $quot_open='\'';
	public $quot_close='\'';
	public $set_default_time=0;
	public $std_error='';

	function __construct($size=11)
	{

		$this->size=$size;
		$this->form='PhangoApp\PhaModels\CoreForms\DateForm';

	}

	//The check have 3 parts, in a part you have a default time, other part if you have an array from a form, last part if you send a timestamp directly.
	
	function check($value)
	{

		$final_value=0;

		if($this->set_default_time==0)
		{

			$final_value=mktime(date('H'), date('i'), date('s'));
		
		}
		
		if(gettype($value)=='array')
		{
			
			settype($value[0], 'integer');
			settype($value[1], 'integer');
			settype($value[2], 'integer');
			settype($value[3], 'integer');
			settype($value[4], 'integer');
			settype($value[5], 'integer');
			
			if($value[0]>0 && $value[1]>0 && $value[2]>0)	
			{

				/*$substr_time=$user_data['format_time']/3600;
	
				$value[3]-=$substr_time;*/

				$final_value=mktime ($value[3], $value[4], $value[5], $value[1], $value[0], $value[2] );
	
			}
			
			/*echo date('H-i-s', $final_value);
			
			//echo $final_value;
			
			die;*/

		}
		else if(strpos($value, '-')!==false)
		{
		
			$arr_time=explode('-',trim($value));
			
			settype($arr_time[0], 'integer');
			settype($arr_time[1], 'integer');
			settype($arr_time[2], 'integer');
			
			$final_value=mktime (0, 0, 0, $arr_time[1], $arr_time[0], $arr_time[2] );
			
			if($final_value===false)
			{
			
				$final_value=mktime (0, 0, 0, $arr_time[1], $arr_time[2], $arr_time[0] );
			
			}
		
		}
		else
		if(gettype($value)=='string' || gettype($value)=='integer')
		{
			
			settype($value, 'integer');
			$final_value=$value;

		}
		
		$this->value=Utils::form_text($final_value);

		return $final_value;

	}

	function get_type_sql()
	{

		return 'INT('.$this->size.') NOT NULL DEFAULT "0"';
		

	}
	
	/**
	* This function is used for show the value on a human format
	*/

	public function show_formatted($value)
	{

		return $this->format_date($value);

	}
	
	static public function format_date($value)
	{

		load_libraries(array('form_date'));
		
		return form_date( $value );
	
	}

	function get_parameters_default()
	{

		return array($this->name_component, '', time());

	}
	
}

?>