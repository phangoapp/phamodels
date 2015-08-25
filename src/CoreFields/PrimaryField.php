<?php

namespace PhangoApp\PhaModels\CoreFields;
use PhangoApp\PhaUtils\Utils;

/**
* PrimaryField is used for primary keys for models
*
* PrimaryField is the most important and the only component used by default for models.
*/

class PrimaryField extends PhangoField {
	
	/**
	* Initial value for the field.
	*/
	
	public $value=0;
	
	/**
	* Initial label for the field. The label is used for create forms from a PhangoField.
	*/
	
	public $label="";
	
	/**
	* Boolean value that is used for check if the field is required for fill a row in the db model.
	*/
	
	public $required=0;
	
	/**
	* By default, the form used for this field is HiddenForm.
	*/
	
	public $form="PhangoApp\PhaModels\CoreForms::HiddenForm";

	/**
	* By default this field is protected.
    */
	
	public $protected=true;
	
	/**
	* Check function that convert the value on a PrimaryField value.
	*
	* @param string $value The value to convert on a PrimaryField value.
	*/
	
	public function check($value)
	{

		$this->value=Utils::form_text($value);
		settype($value, "integer");
		return $value;

	}
	
	/**
	* Method for return the sql type for this PhangoField
	*/
	
	public function get_type_sql()
	{

		return 'INT PRIMARY KEY AUTO_INCREMENT';

	}
	
	/**
	* Method for return a formatted value readable for humans.
	*/

	public function show_formatted($value)
	{

		return $value;

	}

}

?>