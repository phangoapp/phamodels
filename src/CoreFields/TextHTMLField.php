<?php

namespace PhangoApp\PhaModels\CoreFields;
use PhangoApp\PhaUtils\Utils;

/**
* TextHTMLfield is a field for long text values based in html.
*/

class TextHTMLField extends PhangoField {

	public $value="";
	public $label="";
	public $required=0;
	public $quot_open='\'';
	public $quot_close='\'';
	public $std_error='';
	public $multilang=0;

	//This variable is used for write rules what accept html tags

	public $allowedtags=array();

	function __construct($multilang=0)
	{

		$this->form='PhangoApp\PhaModels\Forms\TextAreaEditor';
		
		$this->set_safe_html_tags();

	}

	function check($value)
	{
		
		//Delete Javascript tags and simple quotes.
		
		$txt_without_tags=str_replace('&nbsp;', '', strip_tags($value, '<img>') );
		
		$txt_without_tags=trim(str_replace(' ', '', $txt_without_tags));
		
		if($txt_without_tags=='')
		{
		
            $this->error=true;
			return '';
		
		}

		if(Utils::$textbb_type=='')
		{
			
			$this->value=Utils::unform_text($value);

		}
		else
		{
			
			$this->value=$value;

		}
		
		$value=Utils::form_text_html($value, $this->allowedtags);
		
		if($value=='')
		{
		
            $this->error=1;
		
		}
		
		return $value;

	}

	//Methot for show the allowed html tags to the user

	function show_allowedtags()
	{

		$arr_example_tags=array();

		foreach($this->allowedtags as $tag => $arr_tag)
		{

			$arr_example_tags[]=htmlentities($arr_tag['example']);

		}
		
		return implode(', ', $arr_example_tags);

	}

	function get_type_sql()
	{

		return 'TEXT NOT NULL DEFAULT ""';
		

	}
	
	/**
	* This function is used for show the value on a human format
	*/

	public function show_formatted($value)
	{

		return $value;

	}

	function get_parameters_default()
	{

		return array($this->name_component, '', '');

	}

	function set_safe_html_tags()
	{

		$this->allowedtags['a']=array('pattern' => '/&lt;a.*?href=&quot;(http:\/\/.*?)&quot;.*?&gt;(.*?)&lt;\/a&gt;/', 'replace' => '<a_tmp href="$1">$2</a_tmp>', 'example' => '<a href=""></a>');
		$this->allowedtags['p']=array('pattern' => '/&lt;p.*?&gt;(.*?)&lt;\/p&gt;/s', 'replace' => '<p_tmp>$1</p_tmp>','example' => '<p></p>');
		$this->allowedtags['br']=array('pattern' => '/&lt;br.*?\/&gt;/', 'replace' => '<br_tmp />', 'example' => '<br />');
		$this->allowedtags['strong']=array('pattern' => '/&lt;strong.*?&gt;(.*?)&lt;\/strong&gt;/s', 'replace' => '<strong_tmp>$1</strong_tmp>', 'example' => '<strong></strong>');
		$this->allowedtags['em']=array('pattern' => '/&lt;em.*?&gt;(.*?)&lt;\/em&gt;/s', 'replace' => '<em_tmp>$1</em_tmp>', 'example' => '<em></em>');
		$this->allowedtags['i']=array('pattern' => '/&lt;i.*?&gt;(.*?)&lt;\/i&gt;/s', 'replace' => '<i_tmp>$1</i_tmp>', 'example' => '<i></i>');
		$this->allowedtags['u']=array('pattern' => '/&lt;u.*?&gt;(.*?)&lt;\/u&gt;/s', 'replace' => '<u_tmp>$1</u_tmp>', 'example' => '<u></u>');
		$this->allowedtags['blockquote']=array('pattern' => '/&lt;blockquote.*?&gt;(.*?)&lt;\/blockquote&gt;/s', 'replace' => '<blockquote_tmp>$1</blockquote_tmp>', 'example' => '<blockquote></blockquote>', 'recursive' => 1);
		//$this->allowedtags['img']=array('pattern' => '/&lt;img.*?alt=&quot;([aA-zZ]+)&quot;.*?src=&quot;('.str_replace('/', '\/', PhangoVar::$base_url).'\/media\/smileys\/[^\r\n\t<"].*?)&quot;.*?\/&gt;/', 'replace' => '<img_tmp alt="$1" src="$2"/>', 'example' => '<img alt="emoticon" src="" />');	

	}
	
}

?>