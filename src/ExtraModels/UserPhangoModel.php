<?php
/**
*
* @author  Antonio de la Rosa <webmaster@web-t-sys.com>
* @file
* @package ExtraUtils/Login
*
* Now, we define components for use in models. Components are fields on a table.
*
*/

namespace PhangoApp\PhaModels\ExtraModels;

use PhangoApp\PhaI18n\I18n;
use PhangoApp\PhaModels\Webmodel;
use PhangoApp\PhaUtils\Utils;

I18n::load_lang('users');

/**
* Children class of webmodel for use with login class
*
*/

class UserPhangoModel extends Webmodel {

	public $username='username';
	public $email='email';
	public $password='password';
	public $repeat_password='repeat_password';

	public function insert($post, $safe_query=0, $cache_name='')
	{
        
		if($this->check_user_exists($post[$this->username], $post[$this->email]))
		{
		
			if(!$this->check_password($post['password'], $post['repeat_password']))
			{
			
				//$this->components['password']->required=0;
				
				$this->forms[$this->password]->std_error=I18n::lang('users', 'pasword_not_equal_repeat_password', 'Passwords are not equal');
				
				return false;
			
			}
            
            if(trim($post['password'])!='')
            {
                
                $this->components[$this->password]->protected=false;
                
            }
		
			return parent::insert($post, $safe_query, $cache_name);
		
		}
		else
		{
		
			$this->std_error=I18n::lang('users', 'cannot_insert_user_email_or_user', 'A user already exists with this email or username');
		
			return false;
		
		}
	
	}
	
	public function update($post, $safe_query=0, $cache_name='')
	{
	
		if(isset($post[$this->username]) && $post[$this->email])
		{
	
            if(!isset($post[$this->idmodel]))
            {
            
                settype($_GET[$this->idmodel], 'integer');
            
                $post[$this->idmodel]=$_GET[$this->idmodel];
            
            }
	
			if($this->check_user_exists($post[$this->username], $post[$this->email], $post[$this->idmodel]))
			{
			
				if(!$this->check_password($post[$this->password], $post[$this->repeat_password]))
				{
				
					//$this->components['password']->required=0;
					
					$this->forms[$this->password]->std_error=I18n::lang('users', 'pasword_not_equal_repeat_password', 'Passwords are not equal');
					
					return false;
				
				}
				
				if(Utils::form_text($post['password'])=='')
				{
				
					$this->components[$this->password]->required=0;
					unset($post[$this->password]);
				
				}
                else
                {
                    
                
                    $this->components[$this->password]->protected=false;
                
                    
                }
                
				return parent::update($post, $safe_query, $cache_name);
			
			}
			else
			{
			
				$this->std_error=I18n::lang('users', 'cannot_insert_user_email_or_user', 'A user already exists with this email or username');
			
				return false;
			
			}
			
		}
		else
		{
		
			return parent::update($post, $safe_query);
		
		}
	
	}
	
	public function check_password($password, $repeat_password)
	{
	
		$password=Utils::form_text($password);
		$repeat_password=Utils::form_text($repeat_password);
		
		if($password!=$repeat_password)
		{
		
			return false;
		
		}
		
		return true;
	
	}
	
	public function check_user_exists($user, $email, $iduser=0)
	{
	
		$user=$this->components[$this->username]->check($user);
		$email=$this->components[$this->email]->check($email);
		
		$where_sql='where ('.$this->username.'="'.$user.'" or '.$this->email.'="'.$email.'")';
		
		settype($iduser, 'integer');
		
		if($iduser>0)
		{
		
			$where_sql.=' and '.$this->idmodel.'!='.$iduser;
		
		}
		
        $old_conditions=$this->conditions;
        
		$this->set_conditions($where_sql);
		
		$c=$this->select_count();
        
        $this->conditions=$old_conditions;
		
		if($c==0)
		{
		
			return true;
		
		}
		else
		{
		
			return false;
		
		}
		
	
	}

}

?>
