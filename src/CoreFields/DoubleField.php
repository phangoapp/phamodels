<?php

/**
* Doublefield is a field for doubles values.
*/

namespace PhangoApp\PhaModels\CoreFields;
use PhangoApp\PhaUtils\Utils;

class DoubleField extends PhangoField {

	public $size=11;
	public $value=0;
	public $label="";
	public $required=0;
	public $form="";
	public $quot_open='\'';
	public $quot_close='\'';
	public $std_error='';

	function __construct($size=11)
	{

		$this->size=$size;
		$this->form='PhangoApp\PhaModels\Forms\BaseForm';

	}

	function check($value)
	{

		$this->value=Utils::form_text($value);
		settype($value, "double");
		return $value;

	}

	function get_type_sql()
	{

		return 'DOUBLE NOT NULL DEFAULT "0"';

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