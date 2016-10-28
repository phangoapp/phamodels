<?php

namespace PhangoApp\PhaModels\CoreFields;
use PhangoApp\PhaUtils\Utils;
use PhangoApp\PhaTime;

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

	function __construct()
	{

		$this->size=14;
		$this->form='PhangoApp\PhaModels\Forms\DateForm';

	}

	//The check have 3 parts, in a part you have a default time, other part if you have an array from a form, last part if you send a timestamp directly.
	
	function check($value)
	{
        
		$final_value=0;
        
		/*if($this->set_default_time==0)
		{

			$final_value=PhaTime\DateTime::local_to_gmt(date(PhaTime\DateTime::$sql_format_time));
		
		}*/
		
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
                
                $value=date(PhaTime\DateTime::$sql_format_time, mktime($value[3], $value[4], $value[5], $value[1], $value[0], $value[2]));
                
				//$value=PhaTime\DateTime::format_timestamp($new_timestamp);
                
			}
            else
            {
                
                $value=PhaTime\DateTime::now(true);
                
            }

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
                $this->error=1;
                $final_value=PhaTime\DateTime::local_to_gmt(date(PhaTime\DateTime::$sql_format_time));
			}
		
		}
        if(PhaTime\DateTime::obtain_timestamp($value))
        {
    
            $final_value=PhaTime\DateTime::local_to_gmt($value); 
            
        }
		
		if($final_value==0)
		{
		
            $this->error=1;
            $final_value=PhaTime\DateTime::local_to_gmt(date(PhaTime\DateTime::$sql_format_time));
		
		}

		return $final_value;

	}

	function get_type_sql()
	{

		return 'VARCHAR('.$this->size.') NOT NULL DEFAULT ""';
		

	}
	
	/**
	* This function is used for show the value on a human format
	*/

	public function show_formatted($value)
	{

		return PhaTime\DateTime::format_date($value);

	}
	

	function get_parameters_default()
	{

		return array($this->name_component, '', date(PhaTime\DateTime::$sql_format_time));

	}
	
}

?>
