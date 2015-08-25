<?php

/**
* Emailfield is a field that only accepts emails
*/

namespace PhangoApp\PhaModels\CoreFields;
use PhangoApp\PhaUtils\Utils;

class EmailField extends PhangoField {

	public $size=200;
	public $value="";
	public $label="";
	public $form="PhangoApp\PhaModels\CoreForms::TextForm";
	public $class="";
	public $required=0;
	public $quot_open='\'';
	public $quot_close='\'';
	public $std_error='';

	function __construct($size=200)
	{

		$this->size=$size;

	}

	//Method for accept valid emails only
	
	function check($value)
	{
		
		//Delete Javascript tags and simple quotes.

		

		$value=Utils::form_text($value);

		$this->value=$value;

		$email_expression='([\w\!\#$\%\&\'\*\+\-\/\=\?\^\`{\|\}\~]+\.)*(?:[\w\!\#$\%\'\*\+\-\/\=\?\^\`{\|\}\~]|&amp;)+@((((([a-z0-9]{1}[a-z0-9\-]{0,62}[a-z0-9]{1})|[a-z])\.)+[a-z]{2,6})|(\d{1,3}\.){3}\d{1,3}(\:\d{1,5})?)';
		
		if(preg_match('/^'.$email_expression.'$/i', $value))
		{
			
			return $value;

		}
		else
		{
			
			$this->std_error.='Email format error';
			
			return '';

		}
		

	}

	function get_type_sql()
	{

		return 'VARCHAR('.$this->size.') NOT NULL';

	}

	/**
	* This function is used for show the value on a human format
	*/
	
	public function show_formatted($value)
	{

		return $value;

	}
	
	/**
    * By default primaryfield use a hidden form
    */
    
    public function create_form()
    {
    
        /*$form=new PasswordForm($this->name_component, $this->value);
        $form->default_value=$this->default_value;
        $form->required=$this->required;
        $form->label=$this->label;
        $form->type='password';*/
        
        $form=parent::create_form();
        
        $form->field=new EmailField();
        
        return $form;
    
    }


}

?>