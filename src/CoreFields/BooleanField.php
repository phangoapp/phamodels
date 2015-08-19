<?php

/**
* Booleanfield is a field for boolean values.
*/

namespace PhangoApp\PhaModels\CoreFields;

use PhangoApp\PhaI18n\I18n;

class BooleanField extends PhangoField {

	public $size=1;
	public $value=0;
	public $label="";
	public $required=0;
	public $form="";
	public $quot_open='\'';
	public $quot_close='\'';
	public $std_error='';
	public $default_value=0;

	function __construct()
	{

		$this->size=1;
		$this->form='CoreForms::SelectForm';

	}

	function check($value)
	{

		//$this->value=form_text($value);
		settype($value, "integer");

		if($value!=0 && $value!=1)
		{

			$value=0;

		}

		return $value;

	}

	function get_type_sql()
	{

		//Int for simple compatibility with sql dbs.
	
		return 'INT('.$this->size.') NOT NULL';

	}
	
	/**
	* This function is used for show the value on a human format
	*/

	public function show_formatted($value)
	{

		switch($value)
		{
			default:

				return I18n::lang('common', 'no', 'No');

			break;

			case 1:

				return I18n::lang('common', 'yes', 'Yes');

			break;

	
		}

	}

	function get_parameters_default()
	{
	
		$arr_values=array($this->default_value, I18n::lang('common', 'no', 'No'), 0, I18n::lang('common', 'yes', 'Yes'), 1);;

		return array($this->name_component, '', $arr_values);

	}

}

?>