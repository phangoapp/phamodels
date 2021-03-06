<?php

namespace PhangoApp\PhaModels\CoreFields;
use PhangoApp\PhaUtils\Utils;
use PhangoApp\PhaI18n\I18n;
use PhangoApp\PhaModels\Webmodel;
use Intervention\Image\ImageManager;

/**
* Imagefield is a field for upload images
* 
* This field don't have for now a maximum width and height. To fix in next releases.
*/

class ImageField extends PhangoField {

	public $form='PhangoApp\PhaModels\Forms\FileForm';
	public $path="";
	public $url_path="";
	public $type='';
	public $thumb=0;
	public $img_width=array('mini' => 150);
	public $std_error='';
	public $quality_jpeg=85;
	public $min_size=array(0, 0);
	public $prefix_id=1;
	public $img_minimal_height=array();
	public $func_token='PhangoApp\PhaUtils\Utils::get_token';
	public $move_file_func='move_uploaded_file';
	public $size=255;
	public $driver='gd';

	function __construct($path, $url_path, $thumb=0, $img_width=array('mini' => 150), $quality_jpeg=85)
	{

		#$this->name_file=$this->name_component;
		$this->path=$path;
		$this->url_path=$url_path;
		$this->thumb=$thumb;
		$this->img_width=$img_width;
		$this->quality_jpeg=$quality_jpeg;

	}

	/**
    * Check if the image is correct.
    * 
    * This method check the image and save in a selected folder
    */
	
	function check($fake_image)
	{	
		//Only accept jpeg, gif y png
		
		//Rewrite old_image
		
		$file_name=$this->name_component.'_file';
		
		$old_image='';
		
		if($this->update)
        {
        
            //Check the image for delete.
            //This field is used only for a row
            //echo $this->model_instance->conditions; die;
            $old_reset=Webmodel::$model[$this->name_model]->reset_conditions;
            Webmodel::$model[$this->name_model]->reset_conditions=0;
            $old_image=Webmodel::$model[$this->name_model]->select_a_row_where(array($this->name_component), 1)[$this->name_component];
            Webmodel::$model[$this->name_model]->reset_conditions=$old_reset;
            
            
            
        }
		
		if(isset($_FILES[$file_name]['tmp_name']))
		{
            
			if(trim($_FILES[$file_name]['tmp_name'])!=='')
			{
                if(is_uploaded_file($_FILES[$file_name]['tmp_name']))
                {

                    $name_image=$_FILES[$file_name]['name'];
                    
                    $base_name_image=basename($name_image);
                            
                    $file_extension=pathinfo($base_name_image, PATHINFO_EXTENSION);
                    
                    $base_name_image=str_replace('.'.$file_extension, '', $base_name_image);
                    
                    $name_image=$base_name_image.'.jpg';
                    
                    if($this->prefix_id)
                    {
                        
                        $name_image=hash('sha256', (call_user_func_array($this->func_token, array(25)))).'_'.$name_image;
                        
                    }
                
                    $manager = new ImageManager(array('driver' => $this->driver));
                    
                    if( ($image=$manager->make($_FILES[$file_name]['tmp_name']))!=false)
                    {
                        
                        if($old_image!='')
                        {
                        
                            if(!@unlink($this->path.'/'.$old_image))
                            {
                                $this->std_error=I18n::lang('common', 'cannot_delete_old_image', 'Cannot delete old images, please, check permissions');
                            }
                            
                            $base_old_image=basename($old_image);
                            
                            foreach($this->img_width as $prefix => $width)
                            {
                            
                                if(!@unlink($this->path.'/'.$prefix.'_'.$base_old_image))
                                {
                                
                                   // $this->error=true;
                                
                                    $this->std_error=I18n::lang('common', 'cannot_delete_old_image', 'Cannot delete old thumb images, please, check permissions');
                                }
                            
                            }
                        }
                        
                        $image->backup();
                        
                        //$with=
                        
                        //if(make('foo.jpg')->resize(300, 200)->save('bar.jpg');
                        
                        $real_size=$image->width();
                        
                        $max_size=0; 
                        
                        if(isset($this->img_width['']))
                        {
                        
                            if($this->img_width['']<$real_size)
                            {
                        
                                $max_size=$this->img_width[''];
                                
                                unset($this->img_width['']);
                        
                            }
                            
                        }
                        
                        if($this->thumb)
                        {
                        
                            foreach($this->img_width as $prefix => $width)
                            {
                            
                                $image->reset();
                            
                                //In nexts versions, save in tmp and move with ftp copy.
                            
                                if(!$image->resize($width, null, function ($constraint) {$constraint->aspectRatio();})->encode('jpg', $this->quality_jpeg)->save($this->path.'/'.$prefix.'_'.$name_image))
                                {
                                
                                    $this->error=true;
                                
                                    $this->std_error=I18n::lang('common', 'cannot_save_images', 'Cannot save images. Please, check permissions');
                                
                                }
                            
                            }
                        
                        }
                        
                        //Copy the image
                        
                        $image->reset();
                        
                        if($max_size>0)
                        {
                        
                            $image->resize( $max_size, null, function ($constraint) {$constraint->aspectRatio();});
                        
                        }
                        
                        if(!$image->encode('jpg', $this->quality_jpeg)->save($this->path.'/'.$name_image))
                        {

                            $this->error=1;
                        
                            $this->std_error=I18n::lang('common', 'cannot_save_images', 'Cannot save images, please, check permissions');
                            
                            return '';
                            
                        }
                        
                        return $name_image;
                        
                    }
                    else
                    {
                    
                        $this->std_error=I18n::lang('common', 'no_valid_image', 'This image is wrong');
                    
                        $this->error=1;
                    
                        return '';
                    
                    }
                }
                else
                {
                    
                    $this->std_error=I18n::lang('common', 'no_valid_image', 'This image is not upload');
                    
                    $this->error=1;
                
                    return '';
                    
                }
            }
            else
            {
            
                return $old_image;
            
            }
        
        }
        
        $this->error=true;
        
        $this->std_error=I18n::lang('common', 'no_image_found', 'No image uploaded, check enctype form');
        
        return '';


	}


	function get_type_sql()
	{

		return 'VARCHAR('.$this->size.') NOT NULL DEFAULT ""';

	}
	
	function show_image_url($value)
	{
  
		return $this->url_path.'/'.$value;

	}
	
	function process_delete_field($model, $name_field)
	{
		
		//die;
		$query=$model->select(array($name_field));
		
		while(list($image_name)=$model->fetch_row($query))
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
	
	public function get_parameters_default()
    {
        
        $this->form_loaded->file_url=$this->url_path;


    }
    
    /**
    * Method for delete all orphan images. You can call this method in a script
    */
    
    public function clean_orphan_images()
    {
        

        
        
        
    }

}

?>
