<?php

namespace PhangoApp\PhaModels\CoreFields;

/** This class can be used for create orders or searchs in mysql if you need other thing distinct to default search of default order (default order don't work fine with serializefields how i18nfield). The programmer have the responsability of update this fields via update or insert method.
*
*/

class SlugifyField extends PhangoField {


    public $value="";
    public $label="";
    public $required=0;
    public $form="TextForm";
    public $quot_open='\'';
    public $quot_close='\'';
    public $std_error='';
    public $type='TEXT';

    static function check($value)
    {
    
        $value=slugify($value);
        
        if($value=='')
        {
        
            $this->error=1;
        
        }
        
        return $value;
    }

    function get_type_sql()
    {

        return $this->type.' NOT NULL DEFAULT ""';
        

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