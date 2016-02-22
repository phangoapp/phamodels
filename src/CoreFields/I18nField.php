<?php

/**
*
* @author  Antonio de la Rosa <webmaster@web-t-sys.com>
* @file i18n_fields.php
* @package ExtraFields\I18nFields
*
*
*/

namespace PhangoApp\PhaModels\CoreFields;

use PhangoApp\PhaI18n\I18n;
use PhangoApp\PhaModels\Forms\MultiLangForm;
use PhangoApp\PhaModels\Forms\TextForm;
use PhangoApp\PhaModels\CoreFields\SlugifyField;

/**
* Multilanguage fields. 
*
* With this field you can create fields for i18n sites.
*/

class I18nField extends PhangoField {

	public $value="";
	public $label="";
	public $required=0;
	public $form="PhangoApp\PhaModels\Forms\MultiLangForm";
	public $quot_open='\'';
	public $quot_close='\'';
	public $std_error='';
	public $related_field='';
	public $type_field='';

	//This method is used for check all members from serialize

	function __construct($type_field)
	{

		$this->type_field=&$type_field;
		$this->parameters=[];

	}

	function check($value)
	{
	
		settype($value, 'array');
		
		foreach(I18n::$arr_i18n as $lang_item)
		{

			settype($value[$lang_item], 'string');
		
			$value[$lang_item]=$this->type_field->check($value[$lang_item]);

		}

		if($this->required==1 && $value[I18n::$language]=='')
		{

			$this->std_error=I18n::lang('common', 'error_you_need_this_language_field', 'Error, you need this language field').' '.I18n::$language;
            $this->error=1;
			return '';

		}
		
		$ser_value=addslashes(serialize($value));

		return $ser_value;

	}
	

	function get_type_sql()
	{

		return 'TEXT NOT NULL DEFAULT ""';
		

	}

	static function show_formatted($value)
	{

		$arr_lang=@unserialize($value);

		settype($arr_lang, 'array');
		
		settype($arr_lang[I18n::$language], 'string');

		settype($arr_lang[I18n::$language], 'string');

		if($arr_lang[I18n::$language]=='' && $arr_lang[I18n::$language]=='')
		{
			
			//Need  view var with text...
			
			//$arr_lang_first=array_unique($arr_lang);
			foreach($arr_lang as $key_lang => $val_lang)
			{
			
				if($val_lang!='')
				{
				
					return $val_lang;
				
				}
			
			}

		}
		else if($arr_lang[I18n::$language]=='')
		{
			
			return $arr_lang[I18n::$language];
		
		}
		
		return $arr_lang[I18n::$language];

	}
	
	function add_slugify_i18n_post($field, $post)
	{
	
        $slugfield=new SlugifyField();
	
		foreach(I18n::$arr_i18n as $lang_field)
		{
		
			$post[$field.'_'.$lang_field]=$slugfield->check($post[$field][$lang_field]);
		
		}
		
		return $post;
	
	}
	
}




?>
