<?php

/**
* CharField is a PhangoField that define a varchar element in the model-table.
* 
* A simple PhangoField that define in the database a varchar element with the size that you like.
*/

namespace PhangoApp\PhaModels\CoreFields;
use PhangoApp\PhaUtils\Utils;

class CharField extends PhangoField {

	//Basic variables that define the field

	/**
	* Size of field in database
	*/
	public $size=20;
	

	/**
	* Construct field with basic data...
	*
	* @param integer $size The size of the varchar. If you put 250, for example, you will can put strings with 250 characters on this.
	* @param boolean $multilang Don't use, don't need for nothing.
	*
	*/

	function __construct($size=20)
	{

		$this->size=$size;
		$this->form='PhangoApp\PhaModels\Forms\BaseForm';

	}
	
	/**
	* This function is used for show the value on a human format
	*/

	public function show_formatted($value)
	{

		return $value;

	}
	
	/**
	* This function is for check if the value for field is valid
	* If required and empty check how false
	*/

	public function check($value)
	{

		//Delete Javascript tags and simple quotes.
		$this->value=Utils::form_text($value);
		
		if($value=='')
		{
		
            $this->error=1;
		
		}
		
		return Utils::form_text($value);

	}


}

?>