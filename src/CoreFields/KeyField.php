<?php

namespace PhangoApp\PhaModels\CoreFields;
use PhangoApp\PhaUtils\Utils;

/**
* Keyfield is a indexed field in a sql statement...
*/

class KeyField extends PhangoField {

	public $size=11;
	public $value=0;
	public $label="";
	public $required=0;
	public $form="";
	public $quot_open='\'';
	public $quot_close='\'';
	public $fields=array();
	public $table='';
	public $model='';
	public $ident='';
	public $std_error='';

	function __construct($size=11)
	{

		$this->size=$size;
		$this->form='PhangoApp\PhaModels\Forms\BaseForm';

	}

	function check($value)
	{

		$this->value=Utils::form_text($value);

		settype($value, "integer");
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

}

?>