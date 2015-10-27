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

}

?>