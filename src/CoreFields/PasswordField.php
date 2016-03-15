<?php

/**
*
* @author  Antonio de la Rosa <webmaster@web-t-sys.com>
* @file
* @package ExtraFields
*
*/

namespace PhangoApp\PhaModels\CoreFields;
use PhangoApp\PhaModels\Forms\PasswordForm;
use PhangoApp\PhaUtils\Utils;
use PhangoApp\PhaI18n\I18n;

class PasswordField extends CharField {

	
	function __construct($size=255)
	{
        $this->min_length=5;
		$this->size=$size;
        $this->protected=true;
		$this->form='PhangoApp\PhaModels\Forms\PasswordForm';

	}

	public function check($value)
	{
	
		$value=trim($value);
		
		if($value=='')
		{
            $this->error=1;
            
			return '';
		
		}

		/*
		$token_pass=Utils::generate_random_password();
		
		$hash_password=$token_pass.'_'.sha1($token_pass.'_'.$value);
		*/
		
		if(strlen($value)<$this->min_length)
		{
		
            $this->error=1;
		
            $this->std_error=I18n::lang('common', 'password_min_length', 'Minimal password length:').' '.$this->min_length;
            
            return '';
		
		}
		
		$hash_password=password_hash($value, PASSWORD_DEFAULT);
		
		return $hash_password;

	}
	
	//I load the password with the username and check here.
	
	static public function check_password($value, $hash_password_check)
	{
	
		//If pass have _ check if work fine...
	
		//$token_pass=preg_replace('/(.*)[_].*/', '$1', $hash_password_check);
		/*
		$hash_password=$token_pass.'_'.sha1($token_pass.'_'.$value);
		
		if($hash_password==$hash_password_check)
		{
		
			return true;
		
		}*/
		
		if(password_verify($value, $hash_password_check))
		{
		
            return true;
		
		}
		
		return false;
	
	}
	
	/**
    * By default primaryfield use a hidden form
    */
    
    public function create_form()
    {
    
        $form=new PasswordForm($this->name_component, $this->value);
        $form->default_value=$this->default_value;
        $form->required=$this->required;
        $form->label=$this->label;
        $form->type='password';
        
        return $form;
    
    }


}

?>
