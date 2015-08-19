<?php

namespace PhangoApp\PhaModels\CoreFields;
use PhangoApp\PhaUtils\Utils;

/**
* Textfield is a field for long text values.
*/

class TextField extends PhangoField {

	public $value="";
	public $label="";
	public $required=0;
	public $form="TextAreaForm";
	public $quot_open='\'';
	public $quot_close='\'';
	public $std_error='';
	public $multilang=0;
	public $br=1;

	function __construct($multilang=0)
	{

		$this->form='PhangoApp\PhaModels\CoreForms::TextAreaForm';
		$this->multilang=$multilang;

	}

	function check($value)
	{
		
		//Delete Javascript tags and simple quotes.
		$this->value=$value;
		return Utils::form_text($value, $this->br);

	}

	//Function check_form

	function get_type_sql()
	{

		return 'TEXT NOT NULL';
		

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

		return array($this->name_component, '', '');

	}
	
}
?>