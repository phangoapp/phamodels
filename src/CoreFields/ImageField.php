<?php

namespace PhangoApp\PhaModels\CoreFields;
use PhangoApp\PhaUtils\Utils;
use PhangoApp\PhaI18n\I18n;

/**
* Imagefield is a field for upload images
* 
* This field don't have for now a maximum width and height. To fix in next releases.
*/

class ImageField extends PhangoField {

	public $value="";
	public $label="";
	public $required=0;
	public $form='PhangoApp\PhaModels\Forms\BaseForm';
	public $name_file="";
	public $path="";
	public $url_path="";
	public $type='';
	public $thumb=0;
	public $img_width=100;
	public $quot_open='\'';
	public $quot_close='\'';
	public $std_error='';
	public $quality_jpeg=75;
	public $min_size=array(0, 0);
	public $prefix_id=1;
	public $img_minimal_height=array();
	public $func_token='Utils::get_token';
	public $move_file_func='move_uploaded_file';

	function __construct($name_file, $path, $url_path, $type, $thumb=0, $img_width=array('mini' => 150), $quality_jpeg=85)
	{

		$this->name_file=$name_file;
		$this->path=$path;
		$this->url_path=$url_path;
		$this->type=$type;
		$this->thumb=$thumb;
		$this->img_width=$img_width;
		$this->quality_jpeg=$quality_jpeg;

	}

	//Check if the image is correct..
	
	function check($image)
	{	
		//Only accept jpeg, gif y png
		
		
		
		$file=$this->name_file;
		$image=basename($image);

		settype($_POST['delete_'.$file], 'integer');
		
		if($_POST['delete_'.$file]==1)
		{

			//Delete old_image

			$image_file=Utils::form_text($_POST[$file]);

			if($image_file!='')
			{

				@unlink($this->path.'/'.$image_file);
				
				foreach($this->img_width as $key => $value)
				{

					@unlink($this->path.'/'.$key.'_'.$image_file);
				
				}

				$image='';

			}

		}
		
		if(isset($_FILES[$file]['tmp_name']))
		{
				
			if($_FILES[$file]['tmp_name']!='')
			{	
			
			
				$arr_image=getimagesize($_FILES[$file]['tmp_name']);
				
				$_FILES[$file]['name']=Utils::slugify(Utils::form_text($_FILES[$file]['name']));
				
				if($this->prefix_id==1)
				{
				
					$func_token=$this->func_token;
				
					$_FILES[$file]['name']=$func_token().'_'.$_FILES[$file]['name'];
				
				}
				
				$this->value=$_FILES[$file]['name'];
				
				//Check size
				
				if($this->min_size[0]>0 && $this->min_size[1]>0)
				{
				
					if($arr_image[0]<$this->min_size[0] || $arr_image[1]<$this->min_size[1])
					{
					
						$this->std_error=I18n::lang('common', 'image_size_is_not_correct', 'Image size is wrong').'<br />'.I18n::lang('common', 'min_size', 'Minimal size').': '.$this->min_size[0].'x'.$this->min_size[1];
						
						$this->value='';
						return '';
						
					
					}
				
				}
				
				//Delete other image if exists..
				
				if($image!='')
				{
				
					unlink($this->path.'/'.$image);
				
				}
				
				//gif 1
				//jpg 2
				//png 3
				//Only gifs y pngs...
				
				//Need checking gd support...
				
				$func_image[1]='imagecreatefromgif';
				$func_image[2]='imagecreatefromjpeg';
				$func_image[3]='imagecreatefrompng';
				
				if($arr_image[2]==1 || $arr_image[2]==2 || $arr_image[2]==3)
				{
				
					$image_func_create='imagejpeg';

					switch($arr_image[2])
					{

						case 1:

							//$_FILES[$file]['name']=str_replace('.gif', '.jpg', $_FILES[$file]['name']);
							$image_func_create='imagegif';

						break;

						case 3:

							//$_FILES[$file]['name']=str_replace('.png', '.jpg', $_FILES[$file]['name']);
							$image_func_create='imagepng';
							//Make conversion to png scale
							$this->quality_jpeg=floor($this->quality_jpeg/10);
							
							if($this->quality_jpeg>9)
							{
							
								$this->quality_jpeg=9;
							
							}

						break;

					}
					
					$move_file_func=$this->move_file_func;

					
					if( $move_file_func ( $_FILES[$file]['tmp_name'] , $this->path.'/'.$_FILES[$file]['name'] ))
					{
						
						//Make jpeg.

						$func_final=$func_image[$arr_image[2]];

						$img = $func_final($this->path.'/'.$_FILES[$file]['name']);
						
						//imagejpeg ( $img, $this->path.'/'.$_FILES[$file]['name'], $this->quality_jpeg );
						
						/*$mini_photo=$_FILES[$file]['name'];
				
						$mini_photo=str_replace('.gif', '.jpg', $mini_photo);
						$mini_photo=str_replace('.png', '.jpg', $mini_photo);*/
						
						//Reduce size for default if $this->img_width['']
						
						if(isset($this->img_width['']))
						{
							if($arr_image[0]>$this->img_width[''])
							{
								$width=$this->img_width[''];
							
								$ratio = ($arr_image[0] / $width);
								$height = round($arr_image[1] / $ratio);
							
								$thumb = imagecreatetruecolor($width, $height);
								
								imagecopyresampled ($thumb, $img, 0, 0, 0, 0, $width, $height, $arr_image[0], $arr_image[1]);
								
								$image_func_create ( $thumb, $this->path.'/'.$_FILES[$file]['name'], $this->quality_jpeg );
								
							}
							
							unset($this->img_width['']);
						}

						//Make thumb if specific...
						if($this->thumb==1)
						{
							
							//Convert to jpeg.
							
							foreach($this->img_width as $name_width => $width)
							{
							
								$ratio = ($arr_image[0] / $width);
								$height = round($arr_image[1] / $ratio);
								
								if(isset($this->img_minimal_height[$name_width]))
								{
									
									if($height<$this->img_minimal_height[$name_width])
									{
											
										//Need recalculate the adecuate width and height.
										
										$height=$this->img_minimal_height[$name_width];
										
										$ratio=($arr_image[1] / $height);
										
										$width=round($arr_image[0]/$ratio);
										
										//$width=
									
									}
								
								}
							
								$thumb = imagecreatetruecolor($width, $height);
								
								imagecopyresampled ($thumb, $img, 0, 0, 0, 0, $width, $height, $arr_image[0], $arr_image[1]);
								
								$image_func_create ( $thumb, $this->path.'/'.$name_width.'_'.$_FILES[$file]['name'], $this->quality_jpeg );
								;
								//imagepng ( resource $image [, string $filename [, int $quality [, int $filters ]]] )

							}

						}
						
						//unlink($_FILES[$file]['tmp_name']);
						
						//Unlink if exists image
						
						if(isset($_POST[$file]))
						{
						
							if($_POST[$file]!='')
							{
								$image_file=Utils::form_text($_POST[$file]);

								if($image_file!='')
								{

									@unlink($this->path.'/'.$image_file);
									
									foreach($this->img_width as $key => $value)
									{

										@unlink($this->path.'/'.$key.'_'.$image_file);
									
									}

									$image='';

								}
						
							}
						
						}
						
						return $_FILES[$file]['name'];

						//return $this->path.'/'.$_FILES[$file]['name'];
						
					}
					else
					{

						$this->std_error=I18n::lang('common', 'error_cannot_upload_this_image_to_the_server', 'Error: Cannot upload this image to the server');
						
						if(DEBUG==1)
						{
						
							$this->std_error.=' Image origin '.$_FILES[$file]['tmp_name'].' in this path '.$this->path;
						
						}

						return '';

					}
					

				}
				else
				{

					$this->std_error.=I18n::lang('error_model', 'img_format_error', 'Img format error, only accept gif, jpg and png formats');

				}

			}
			else if($image!='')
			{

				return $image;

			}


		}
		else if($image!=='')
		{
			
			
			if(file_exists($this->path.'/'.$image))
			{

				$this->value=$this->path.'/'.$image;
				return $image;

			}
			else
			{
			
				$this->std_error=I18n::lang('error_model', 'check_error_enctype_for_upload_file', 'Please, check enctype form of file form');
				return '';
			
			}
			
			

		}
		else
		{
		
			$this->std_error=I18n::lang('error_model', 'check_error_enctype_for_upload_file', 'Please, check enctype form of file form');
		
		}

		$this->value='';
		return '';


	}


	function get_type_sql()
	{

		return 'VARCHAR(255) NOT NULL';

	}
	
	function show_image_url($value)
	{
  
		return $this->url_path.'/'.$value;

	}
	
	function process_delete_field($model, $name_field, $conditions)
	{
	
		
		
		//die;
		$query=$model->select($conditions, array($name_field));
		
		while(list($image_name)=webtsys_fetch_row($query))
		{
		
			if( file_exists($this->path.'/'.$image_name) && !is_dir($this->path.'/'.$image_name) )
			{
				if(unlink($this->path.'/'.$image_name))
				{
				
					//Unlink mini_images
					
					unset($this->img_width['']);
					
					foreach($this->img_width as $key => $value)
					{
					
						if(!unlink($this->path.'/'.$key.'_'.$image_name))
						{
							
							$this->std_error.=I18n::lang('common', 'cannot_delete_image', 'Cannot delete the image').': '.$key.'_'.$image_name;
						
						}
					
					}
				
					$this->std_error.=I18n::lang('common', 'cannot_delete_image', 'Cannot delete the image').': '.$image_name;
				
				}
				else
				{
				
					$this->std_error.=I18n::lang('common', 'cannot_delete_image', 'Cannot delete the image').': '.$image_name;
				
				}
				
			}
			else
			{
			
				$this->std_error.=I18n::lang('common', 'cannot_delete_image', 'Cannot delete the image').': '.$image_name;
			
			}
		
		}
	
	}
	
	/**
	* Method for return a formatted value readable for humans.
	*/
	
	public function show_formatted($value)
	{
	
		//Size
		
		$size=150;
	
		if($this->thumb==1)
		{
		
			reset($this->img_width);
			
			if(isset($this->img_width['']))
			{
			
				next($this->img_width);
			
			}
			
			$key=key($this->img_width);
			
			$value=$key.'_'.$value;
			
			$size=$this->img_width[$key];
			
		
		}
	
		return '<img src="'.$this->show_image_url($value).'" width="'.$size.'"/>';
	
	}

}

?>