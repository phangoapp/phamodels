<?php

namespace PhangoApp\PhaModels;
use PhangoApp\PhaUtils\Utils;
use PhangoApp\PhaModels\CoreFields;
use PhangoApp\PhaI18n\I18n;

//Class ModelForm is the base class for create forms...

/**
* ModelForm is a class used for create and manipulate forms.
*
* ModelForm is a class used for create and manipulate forms. With this, you can create a complete html form, check, fill with values, etc..., when you create a ModelForm, you create a field of a form. If you want a form, create an array with ModelForms and 
*
*/

class ModelForm {


	/**
	* The name of the form where is inserted this form element
	* 
	*/

	public $name_form;
	
	/**
	* The name of this ModelForm 
	* 
	*/
	
	public $name;
	
	/**
	* String with the name of the function for show the form. For example 'TextForm'.
	* 
	*/
	
	public $form;
	
	/**
	* Text that is used on html form for identify the field.
	* 
	*/
	
	public $label;
	
	/**
	* Text that is used on html for identify the class label containment.
	* 
	*/
	
	public $label_class='';
	
	/**
	*  DEPRECATED An string used for internal tasks of older versions of generate_admin
	* *@deprecated Used on older versions of generate_admin
	* 
	*/
	
	public $set_form;
	
	/**
	*  String where the error message from a source is stored
	* 
	*/
	
	public $std_error;
	
	/**
	*  String where the default error message is stored if you don't use $this->std_error
	* 
	*/
	
	public $txt_error;
	
	/**
	*  Internal string used for identify fields when name fields protection is used.
	* 
	*/
	
	public $html_field_name='';
	
	/**
	*  Boolean that defined if this ModelForm is required in the form or not. If is required, set to true or to 1.
	* 
	*/
	
	public $required=0;
	
	/**
	*  Internal boolean that control if the field was filled correctly or not.
	* 
	*/
	
	public $error_flag=0;
	
	/**
	* Array for save the fields for external checkings.
	*/
	
	static public $arr_form_public=array();
	
	/**
	* Boolean property that set DEBUG mode
	*/
	
	static public $debug=1;
	
	/**
	* Constructor for create a new ModelForm. ModelForm are used for create forms easily.
	* 
	* @param string $name_form  The name of the form where is inserted this form element
	* @param string $name_field The name of this ModelForm 
	* @param string $form String with the name of the function for show the form. For example 'TextForm'.
	* @param string $label Text that is used on html form for identify the field.
	* @param PhangoField $type PhangoField instance, you need this if you want check the value of the ModelForm.
	* @param boolean $required Internal boolean that control if the field was filled correctly or not.
	* @param array $parameters Set first set of parameters for $this->form. This element cover the third argument of a Form function.
	*
	*/

	function __construct($name_form, $name_field, $form, $label, $type, $required=0, $parameters='')
	{

		$this->name_form = $name_form;
		$this->name = $name_field;
		$this->form = $form;
		$this->type = $type;
		$this->label = $label;
		$this->std_error = '';
		$this->txt_error = I18n::lang('common', 'error_in_field', 'Error in field');
		$this->required = $required;

		$this->html_field_name=$name_field;

		switch(ModelForm::$debug)
		{

			default:
				
				$prefix_uniqid=Utils::generate_random_password();
								
				if(!isset($_SESSION['fields_check'][$name_field]))
				{
					$this->html_field_name=uniqid($prefix_uniqid);
				
					$_SESSION['fields_check'][$name_field]=$this->html_field_name;
					$_SESSION['fields_check'][$this->html_field_name]=$name_field;
					
				}
				else
				{
				
					$this->html_field_name=$_SESSION['fields_check'][$name_field];
				
				}
			
				/*$this->html_field_name[$name_field]=$html_field_name;

				if(isset($_POST[$html_field_name]))
				{

					$_POST[$name_field]=&$_POST[$html_field_name];

				}

				if(isset($_FILES[$html_field_name]))
				{

					$_FILES[$name_field]=&$_FILES[$html_field_name];

				}*/

			break;

			case 1:

				$this->html_field_name=$name_field;
				

			break;
		}
		
		//$this->html_field_name=$name_field; slugify($this->label, $respect_upper=0, $replace_space='-', $replace_dot=1, $replace_barr=1);

		ModelForm::$arr_form_public[$this->html_field_name]=$name_field;

		$this->parameters = array($this->html_field_name, $class='', $parameters);

	}
	
	public function change_label_html($new_label)
	{
	
		$this->html_field_name=slugify($new_label, $respect_upper=0, $replace_space='-', $replace_dot=1, $replace_barr=1);
		
		$this->parameters[0]=$this->html_field_name;
	
	}
	
	public function return_name_form()
	{
	
		return $this->html_field_name;
        
	}
	
	
	/**
	*
	* Method for set default value in the form.
	*
	* @param mixed $value The value passed to the form
	* @param string $form_type_set Parameter don't used for now.
	*
	*/

	public function set_param_value_form($value, $form_type_set='')
	{
		
		$func_setvalue=$this->form.'Set';
		
		//$this->parameters[2]=$func_setvalue($this->parameters[2], $value, $form_type_set);
		$this->parameters[2]=call_user_func_array($func_setvalue, array($this->parameters[2], $value, $form_type_set));
		
	}
	

	/**
	*
	* Method for set third argument of a form function. Third argument can be mixed type.
	*
	* @param mixed $parameters Third argument for the chose form function
	*
	*/
	
	public function set_parameter_value($parameters)
	{
		
		$this->parameters[2]=$parameters;
		
	}
	
	/**
	*
	* Method for set all arguments for a form function except name.
	*
	* @param mixed $parameters Third argument for the chose form function
	*
	*/
	
	public function set_parameters_form($parameters=array())
	{
	
		$z=1;
	
		foreach($parameters as $parameter)
		{
		
			$this->parameters[$z]=$parameter;
			
			$z++;
			
		}
		
	}
	
	public function set_parameter($key, $value)
	{
		
		$this->parameters[$key]=$value;
	
	}
	
	/**
	*
	* @warning 
	*
	* Method for set all arguments of a form function. DONT USE IF YOU DON'T KNOW WHAT ARE YOU DOING
	* 
	* @param array $parameters An array with arguments for the form function used for this ModelForm
	*
	*/
	
	public function set_all_parameters_form($parameters)
	{
		
		$this->parameters=$parameters;
		
	}
	
	/**
	*
	* Static method for check an array of ModelForm instances. 
	*
	* With this method you can check if the values of an array called $post (tipically $_POST) are valid for the corresponding values of an array $arr_form, consisting of ModelForm items.
	*
	* @param array $arr_form Array consisting of ModelForm items, used for check the values. The array need keys with the name of the ModelForm instance.
	* @param array $post Array consisting of values. The array need that the keys was the same of $arr_form.
	*
	*/

	static public function check_form($arr_form, $post)
	{

		$error=0;
		
		$num_form=0;
		
		foreach($post as $key_form => $value_form)
		{
			
			//settype($post[$key_form], 'string');
			
			if(isset($arr_form[$key_form]))
			{
			
				$form=$arr_form[$key_form];
			
				$post[$key_form]=$form->field->check($post[$key_form]);
				
				if($post[$key_form]=='' && $form->required==1)
				{
					
					if($form->field->std_error!='')
					{

						$form->std_error=$form->field->std_error;

					}
					else
					{

						$form->std_error=I18n::lang('common', 'field_required', 'Field is required');

					}
					
					$form->error_flag=1;

					if($form->required==1)
					{

						$error++;

					}
		
				}
				
			}
			
			$num_form++;

		}

		if($error==0 && $num_form>0)
		{

			return $post;

		}
		
		return 0;

	}
	
	/**
	*
	* Fill a ModelForm array with default values.
	*
	* With this method you can set an array consisting of ModelForm items with the values from $post.
	*
	* @param array $post is an array with the values to be inserted on $arr_form. The keys must have the same name that keys from $arr_form
	* @param array $arr_form is an array of ModelForms. The key of each item is the name of the ModelForm item.
	* @param array $show_error An option for choose if in the form is showed 
	*/

	static public function set_values_form($arr_form, $post, $show_error=1)
	{
		
		//Foreach to $post values
		
		if(gettype($post)=='array')
		{
			foreach($post as $name_field => $value)
			{
				
				//If exists a ModelForm into $arr_form with the same name to $name_field check if have a $component field how "type" and set error if exists

				if(isset($arr_form[$name_field]))
				{	
					
					if($arr_form[$name_field]->field->std_error!='' && $show_error==1)
					{
						
						if($arr_form[$name_field]->std_error!='')
						{
							
							$arr_form[$name_field]->std_error=$arr_form[$name_field]->txt_error;
							

						}
						else
						if($arr_form[$name_field]->std_error=='')
						{
							
							$arr_form[$name_field]->std_error=$arr_form[$name_field]->field->std_error;

						}

					}

					//Set value for ModelForm to $value
					
					$arr_form[$name_field]->default_value=$value;
					
			
				}
				else
				{

					unset($post[$name_field]);

				}

			}

		}
	}
	
	static public function pass_errors_to_form($model)
	{
	
        foreach(array_keys($model->components) as $key)
        {
        
            if(isset($model->forms[$key]))
            {
                
                
                $model->forms[$key]->field->std_error=$model->components[$key]->std_error;
            
            }
        
        }
	
	}
		
}

?>