<?php

namespace PhaView;
use PhaUtils\Utils;

class View {

	/**
	* Root path for includes app folders
	*/
	
	public $root_path=__DIR__;
	
	/**
	* A set of paths inside of $root_path contining views. If a view is located, the foreach for search the view is break
	*/
	
	public $folder_env=array('views/default', 'app/views');
	
	/**
	* Array for caching the template call...
	*/
	
	public $cache_template=array();
	
	/**
	* Path of static media files (javascript, images and css).
	*/
	
	public $path_media='media';
	
	/**
	* Url of static media files (javascript, images and css).
	*/
	
	public $url_media='media';
	
	/**
	* Media .php path.
	*/
	
	public $php_file='showmedia.php';

	/**
	* Internal property used for see if this media are in production
	*/
	
	protected $production=0;
	
	/**
	* Internal property used for define the method used for retrieve the media files.
	*/
	
	protected $func_media='dynamicGetMediaUrl';
	
	/**
	* An array where you can add new css in all views. For example, a view that use a special css can use this array and you can insert the value 'special.css' and the principal View can use loadCss method for load all css writed in the array by children views.
	*/
	
	public $css=array();
	
	/**
	* An array where you can add new js in all views. For example, a view that use a special js can use this array and you can insert the value 'special.js' in principal View using loadJs method for load all js writed in the array by children views.
	*/
	
	public $js=array();
	
	/**
	* An array where you can add new code in <header> tag. For example, a view that need a initialitation code in the principal view can use this array and you can insert the code in principal View using loadHeader method for load all header code writed in the array by children views.
	*/
	
	public $header=array();
	
	/**
	* The construct for create a view object
	*
	* @param string $folder_base The folder used how base path for search view files
	*/

	public function __construct()
	{
	
		$arr_arg=func_get_args();
		
		if(count($arr_arg)>0)
		{
			
			$this->folder_env=$arr_arg;
		
		}
	
		$this->root_path=getcwd();

	}
	
	/**
	* Very important function used for load views. Is the V in the MVC paradigm. Phango is an MVC framework and has separate code and html.
	*
	* load_view is used for load the views. Views in Phango are php files with a function that have a special name with "View" suffix. For example, if you create a view file with the name blog.php, inside you need create a php function called BlogView(). The arguments of this function can be that you want, how on any normal php function. The view files need to be saved on a "view" folders inside of a theme folder, or a "views/module_name" folder inside of a module being "module_name" the name of the module.
	*
	* @param array $arr_template_values Arguments for the view function of the view.
	* @param string $template Name of the view. Tipically views/$template.php or modules/name_module/views/name_module/$template.php
	* @param string $module_theme If the view are on a different theme and you don't want put the view on the theme, use this variable for go to the other theme.
	*/

	public function loadView($arr_template_values, $template)
	{

		//First see in controller/view/template, if not see in /views/template
		
		$yes_cache=0;
		
		if(!isset($this->cache_template[$template])) 
		{
		
			foreach($this->folder_env as $base_path)
			{
			
				$view_path=$this->root_path.'/'.$base_path.'/'.$template.'.php';
				
				if(is_file($view_path))
				{
				
					include($view_path);
					
					$yes_cache=1;
					
					break;
				
				}
			
			}
			
			//If load view, save function name for call write the html again without call include view too
			
			if($yes_cache==1)
			{
			
				$this->cache_template[$template]=basename($template).'View';
				
			}
			else
			{
			
				throw new \Exception('Error: view not found: '.$view_path);
				die;
			
			}

		}
		
		ob_start();

		$func_view=$this->cache_template[$template];
		
		//Load function from loaded view with his parameters
		
		array_unshift($arr_template_values, $this);

		call_user_func_array($func_view, $arr_template_values);

		$out_template=ob_get_contents();

		ob_end_clean();
		
		return $out_template;

	}
	
	/**
	*
	*/
	
	public function dynamicGetMediaUrl($path_file)
	{
	
		return $this->php_file.'/'.$path_file;
	
	}
	
	/**
	*
	*/
	
	public function staticGetMediaUrl($path_file)
	{
	
		return $this->url_media.'/'.$path_file;
	
	}
	
	/**
	*
	*/
	
	public function setProduction($value=1)
	{
	
		if($value==1)
		{
			
			$production=1;
			$this->func_media='staticGetMediaUrl';
			
		}
		else
		{
		
			$production=0;
		
			$this->func_media='dynamicGetMediaUrl';
		
		}
	
	}
	
	/**
	*
	*/
	
	public function getMediaUrl($path_file)
	{
	
		$func_media=$this->func_media;
		
		return $this->$func_media($path_file);
	
	}
	
	/**
	* Method for load media files. Method for load simple media file, is only for development
	*
	* This method is used on php files for retrieve media files using a very simple url dispatcher.
	*
	* @warning  NO USE THIS METHOD IN PRODUCTION.
	*
	*/
	
	public function loadMediaFile($url)
	{
	
		//Check files origin.
		
		if($this->production==0)
		{
		
			$yes_file=0;
			
			$arr_url=explode($this->php_file.'/', $url);
			
			$final_path='';
			
			if(isset($arr_url[1]))
			{
			
				//Clean the path of undesirerable elements.
			
				$arr_path=explode('/', $arr_url[1]);
				
				$c=count($arr_path)-1;
				
				//foreach($arr_path as $key_path => $item_path)
				for($x=0;$x<$c-1;$x++)
				{
				
					$arr_path[$key_path]=Utils::slugify($item_path, $respect_upper=0, $replace_space='-', $replace_dot=1, $replace_barr=1);
				
				}
				
				$arr_path[$c]=Utils::slugify($arr_path[$c], $respect_upper=1, $replace_space='-', $replace_dot=0, $replace_barr=1);
				
				$final_path=implode('/', $arr_path);
				
			
			}
			
			foreach($this->folder_env as $folder)
			{
			
				$file_path=$this->root_path.'/'.$folder.'/'.$this->path_media.'/'.$final_path;
				
				if(is_file($file_path))
				{
					$yes_file=1;
					
					break;
				
				}
				
			}
			
			if($yes_file==1)
			{
			
				$ext_info=pathinfo($file_path);
			
				settype($ext_info['extension'], 'string');
				
				switch($ext_info['extension'])
				{
				
					default:
					
						$type_mime='text/plain';
					
					break;
					
					case 'js':
					
						$type_mime='application/javascript';
					
					break;
					
					case 'css':
					
						$type_mime='text/css';
					
					break;
					
					case 'gif':
					
						$type_mime='image/gif';
					
					break;
					
					case 'png':
					
						$type_mime='image/png';
					
					break;
					
					case 'jpg':
					
						$type_mime='image/jpg';
					
					break;
				
				}
				

				
				header('Content-Type: '.$type_mime);
				
				readfile($file_path);
				
				die;
				
			
			}
			else
			{
			
				header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found"); 
					
				echo 'File not found...';
				
				die;
			
			}
		
		}
	
	}
	
	
}

?>