<?php

/**
*
* @author  Antonio de la Rosa <webmaster@web-t-sys.com>
* @file
* @package ExtraFields
*
*/

namespace PhangoApp\PhaModels\CoreFields;

class DateTimeField extends DateField
{

	public function __construct()
	{

		$this->form='PhangoApp\PhaModels\Forms\DateTimeForm';

	}

	public function check($value)
	{
	
		$timestamp=parent::check($value);
		
		$date=@date('YmdHis', $timestamp);
		
		if($date!==false)
		{
            return date('YmdHis', $timestamp);
        }
        else
        {
        
            $this->error=true;
        
            return date('YmdHis');
        
        }
	}
	
	public function search_field($value)
	{
	
		$value_check=$this->check($value);
				
		return substr($value_check, 0, 8);
	
	}
	
	public function show_formatted($value)
	{

		$timestamp=$this->obtain_timestamp_datefield($value);
		
		return parent::show_formatted($timestamp);

	}

	public function get_type_sql()
	{

		return 'VARCHAR(14) NOT NULL DEFAULT ""';
		

	}

	static public function obtain_timestamp_datefield($value)
	{

		$year=substr($value, 0, 4);
		$month=substr($value, 4, 2);
		$day=substr($value, 6, 2);
		$hour=substr($value, 8, 2);
		$minute=substr($value, 10, 2);
		$second=substr($value, 12, 2);

		settype($year, 'integer');
		settype($month, 'integer');
		settype($day, 'integer');
		settype($hour, 'integer');
		settype($minute, 'integer');
		settype($second, 'integer');
		
		$timestamp=mktime($hour, $minute, $second, $month, $day, $year);
		
		return $timestamp;
		
	}
	
}



?>