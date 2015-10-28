<?php

namespace PhangoApp\PhaModels;

use PhangoApp\PhaUtils\Utils;
use PhangoApp\PhaModels\Models;

class Cache {

    static  public function file_cache_query($model_name)
    {
    
        Utils::load_config('query_cache_'.$model_name, Webmodel::$folder_cache);
    
    }
    
    static  public function save_cache_query()
    {
    
        //Utils::load_config('query_cache_'.$model_name, Webmodel::$folder_cache);
        foreach(Webmodel::$model as $model)
        {
        
            if($model->cache_query==1 && $model->count_cache_query>0)
            {
                
                $yes_save=0;
            
                $file="<?php\n\n";
                
                $file.="use PhangoApp\PhaModels\Webmodel;\n\n";
                
                foreach($model->arr_cache_query as $name_cache => $cache_query)
                {
                    
                    
                    $file.="Webmodel::\$model['".$model->name."']->arr_cache_query['".$name_cache."']=\"".addslashes($cache_query)."\";\n\n";
                    
                    $yes_save++;
                
                }
        
                //Open file
                
                if($yes_save>0)
                {
                
                    if(!file_put_contents(Webmodel::$folder_cache.'/query_cache_'.$model->name.'.php', $file))
                    {
                    
                        echo "Error: cannot access to cache folder for save the query caches...\n";
                    
                    }
                    
                }
                //Save file
            }
            
        }
    
    }
    
    static public function check_cache($md5_query, $model_name)
    {
        
        if(!file_exists(Webmodel::$folder_cache.'/'.$md5_query.'_'.$model_name.'.php'))
        {
        
            return false;
        
        }
        
        return true;
    
    }
    
    static public function save_cache($md5_query, $model_name, $query)
    {
    
        $file="<?php\n\n";
                
        $file.="use PhangoApp\PhaModels\Webmodel;\n\n";
        
        while($row=Webmodel::$model[$model_name]->nocached_fetch_array($query))
        {
            
            $file.="Webmodel::\$model['".$model_name."']->arr_cache_row[]=".var_export($row, true).";\n\n";
        
        }
        
        file_put_contents(Webmodel::$folder_cache.'/'.$md5_query.'_'.$model_name.'.php', $file);
    
    }
    
    static public function file_cache($md5_file, $model_name)
    {
        
        include(Webmodel::$folder_cache.'/'.$md5_file.'_'.$model_name.'.php');
    
    }
    
    static public function refresh_cache($model_name, $new_id)
    {
    
        array_map('unlink', glob(Webmodel::$folder_cache.'/'.'*_'.$model_name.'.php'));
    
    }

}

?>