<?php

namespace PhangoApp\PhaModels\CoreFields;
use PhangoApp\PhaUtils\Utils;

/**
*
*/

class FileField extends PhangoField {

	public $value="";
	public $label="";
	public $required=0;
	public $form='PhangoApp\PhaModels\Forms\BaseForm';
	public $name_file="";
	public $path="";
	public $url_path="";
	//public $type='';
	public $quot_open='\'';
	public $quot_close='\'';
	public $std_error='';

	function __construct($name_file, $path, $url_path)
	{

		$this->name_file=$name_file;
		$this->path=$path;
		$this->url_path=$url_path;
		//$this->type=$type;

	}

	//Check if the file is correct..
	
	function check($file)
	{	
		
		$file_field=$this->name_file;

		settype($_POST['delete_'.$file_field], 'integer');
		
		if($_POST['delete_'.$file_field]==1)
		{

			$file_delete=Utils::form_text($_POST[$file_field]);

			if($file_delete!='')
			{

				@unlink($this->path.'/'.$file_delete);

				$file='';

			}

		}
		
		if(isset($_FILES[$file_field]['tmp_name']))
		{
				
			if($_FILES[$file_field]['tmp_name']!='')
			{
	
				if( move_uploaded_file ( $_FILES[$file_field]['tmp_name'] , $this->path.'/'.$_FILES[$file_field]['name'] ) )
				{

					return $_FILES[$file_field]['name'];

					//return $this->path.'/'.$_FILES[$file]['name'];
					
				}
				else
				{

					$this->std_error=I18n::lang('common', 'error_cannot_upload_this_file_to_the_server', 'Error: Cannot upload this file to the server');

					return '';

				}
					

			}
			else if($file!='')
			{

				return $file;

			}

		}
		else
		{
		
			$this->std_error=I18n::lang('error_model', 'check_error_enctype_for_upload_file', 'Please, check enctype form of file form');
		
			return '';
		
		}

		$this->value='';
		
		return '';


	}


	function get_type_sql()
	{

		return 'VARCHAR(255) NOT NULL DEFAULT ""';

	}
	
	/**
	* This function is used for show the value on a human format
	*/

	public function show_formatted($value)
	{

		return $value;

	}
	
	function show_file_url($value)
	{

		return $this->url_path.'/'.$value;

	}

	function get_parameters_default()
	{

		return array($this->name_component, '', '');

	}
	
	function process_delete_field($model, $name_field, $conditions)
	{
	
		$query=$model->select($conditions, array($name_field));
		
		while(list($file_name)=webtsys_fetch_row($query))
		{
		
			if(!unlink($this->path.'/'.$file_name))
			{
			
				$this->std_error=I18n::lang('common', 'cannot_delete_file', 'Cannot delete the file');
			
			}
		
		}
	
	}

}

?>