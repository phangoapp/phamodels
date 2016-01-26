<?php

namespace PhangoApp\PhaModels\CoreFields;

/** This class can be used for create orders or searchs in mysql if you need other thing distinct to default search of default order (default order don't work fine with serializefields how i18nfield). The programmer have the responsability of update this fields via update or insert method.
*
*/

class SlugifyField extends CharField {

    public $form='PhangoApp\PhaModels\Forms\HiddenForm';
    public $field_related='';

    public function check($value)
    {
        
        if($this->model_instance->post!='')
        {
            
            if(isset($this->model_instance->post[$this->field_related]))
            {
                
                $value=\PhangoApp\PhaUtils\Utils::slugify($this->model_instance->post[$this->field_related]);
                
            }
            
        }
        
        if($value=='')
        {
        
            $this->error=1;
        
        }
        
        return $value;
    }
    
    static function add_slugify_i18n_fields($model_name, $field)
    {
    
        foreach(PhangoVar::$arr_i18n as $lang_field)
        {

            PhangoVar::$model[$model_name]->components[$field.'_'.$lang_field]=new SlugifyField();
            
        }
    
    }
    
}

?>