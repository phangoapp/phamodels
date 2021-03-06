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
	public $quot_open='';
	public $quot_close='';
	public $std_error='';
	public $default_value=0;
	public $text_yes='';
	public $text_no='';

	function __construct()
	{

		$this->size=1;
		$this->form='PhangoApp\PhaModels\Forms\SelectForm';
		$this->text_no=I18n::lang('common', 'no', 'No');
		$this->text_yes=I18n::lang('common', 'yes', 'Yes');

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
	
		return 'INT('.$this->size.') NOT NULL DEFAULT "0"';

	}
	
	/**
	* This function is used for show the value on a human format
	*/

	public function show_formatted($value)
	{

		switch($value)
		{
			default:

				return $this->text_no;

			break;

			case 1:

				return $this->text_yes;

			break;

	
		}

	}

	function get_parameters_default()
	{
	
		$this->form_loaded->arr_select=array(0 => I18n::lang('common', 'no', 'No'), 1 => I18n::lang('common', 'yes', 'Yes'));

		

	}

}

?>
