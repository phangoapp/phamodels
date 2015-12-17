<?php

/**
* Integerfield is a field for integers values.
*  
*/

namespace PhangoApp\PhaModels\CoreFields;
use PhangoApp\PhaUtils\Utils;
use PhangoApp\PhaI18n\I18n;

class IntegerField extends PhangoField {

	public $size=11;
	public $value=0;
	public $label="";
	public $required=0;
	public $only_positive=false;
	public $min_num=0;
	public $max_num=0;

	function __construct($size=11, $only_positive=false, $min_num=0, $max_num=0)
	{

		$this->size=$size;
		$this->form='PhangoApp\PhaModels\Forms\BaseForm';
		$this->only_positive=$only_positive;
		$this->min_num=$min_num;
		$this->max_num=$max_num;

	}

	function check($value)
	{

		$this->value=Utils::form_text($value);
		
		settype($value, "integer");
		
		if($this->only_positive==true && $value<0)
		{
		
			$value=0;
		
		}
		
		if($this->min_num<>0 && $value<$this->min_num)
		{   
            $this->std_error=I18n::lang('common', 'no_value', 'The value is wrong. You need a value betwen '.$this->min_num.' and '.$this->max_num);
            
            $this->error=1;
            
			$value=$this->min_num;
		
		}
		
		if($this->max_num<>0 && $value>$this->max_num)
		{
            $this->std_error=I18n::lang('common', 'no_value', 'The value is wrong. You need a value betwen '.$this->min_num.' and '.$this->max_num);
            
            $this->error=1;
            
			$value=$this->max_num;
		
		}
		
		return $value;

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

		return $value;

	}

	function get_parameters_default()
	{

		return array($this->name_component, '', 0);

	}

}

?>