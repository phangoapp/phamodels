<?php

namespace PhangoApp\PhaModels\CoreFields;
use PhangoApp\PhaUtils\Utils;

/**
* NEED TESTING, PROBABLY BROKEN
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
    public $func_token='PhangoApp\PhaUtils\Utils::get_token';
    public $prefix_id=1;

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
		
		if($this->update)
        {
        
            //Check the image for delete.
            //This field is used only for a row
            //echo $this->model_instance->conditions; die;
            $old_reset=Webmodel::$model[$this->name_model]->reset_conditions;
            Webmodel::$model[$this->name_model]->reset_conditions=0;
            $old_file=Webmodel::$model[$this->name_model]->select_a_row_where(array($this->name_component), 1)[$this->name_component];
            Webmodel::$model[$this->name_model]->reset_conditions=$old_reset;
            
        }
		
		if(isset($_FILES[$file_field]['tmp_name']))
		{
				
			if($_FILES[$file_field]['tmp_name']!='')
			{
                
                $name_file=basename($_FILES[$file_field]['tmp_name']);
                
                if($this->prefix_id)
                {
                    
                    $name_file=hash('sha256', (call_user_func_array($this->func_token, array(25)))).'_'.$name_file;
                    
                }
	
				if( move_uploaded_file ( $_FILES[$file_field]['tmp_name'] , $this->path.'/'.$name_file ) )
				{

                    if($old_file!='')
					{
					
						if(!@unlink($this->path.'/'.$old_file))
						{
							$this->std_error=I18n::lang('common', 'cannot_delete_old_file', 'Cannot delete old files, please, check permissions');
						}
						
					}

					return $name_file;

					//return $this->path.'/'.$_FILES[$file]['name'];
					
				}
				else
				{

					$this->std_error=I18n::lang('common', 'error_cannot_upload_this_file_to_the_server', 'Error: Cannot upload this file to the server');

					$this->error=1;
					
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
		
            $this->error=1;
		
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
