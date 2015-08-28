<?php

namespace PhangoApp\PhaModels\Forms;

use PhangoApp\PhaI18n\I18n;
use PhangoApp\PhaUtils\Utils;
use PhangoApp\PhaModels\Forms\BaseForm;
use PhangoApp\PhaView\View;

class MultiLangForm extends BaseForm{

    public $type_form='PhangoApp\PhaModels\Forms\BaseForm';

    public function form()
    {
        //make a foreach with all langs
        //default, es_ES, en_US, show default if no exists translation for selected language.
         foreach(I18n::$arr_i18n as $lang_select)
        {

           /* $arr_selected[Utils::slugify($lang_select)]='hidden_form';
            $arr_selected[Utils::slugify(I18n::$language)]='no_hidden_form';*/
            
            /*settype($arr_values[$lang_select], 'string');
            echo '<div class="'.$arr_selected[Utils::slugify($lang_select)].'" id="'.$this->name.'_'.$lang_select.'">';
            echo $this->type_form($this->name.'['.$lang_select.']', '', $arr_values[$lang_select]);
            echo '</div>';*/

        }
        ?>
        <div id="languages">
        <?php

        $arr_selected=array();

        foreach(I18n::$arr_i18n as $lang_item)
        {
            //set

            $arr_selected[Utils::slugify($lang_item)]='no_choose_flag';
            $arr_selected[Utils::slugify(I18n::$language)]='choose_flag';

            ?>
            <a class="<?php echo $arr_selected[Utils::slugify($lang_item)]; ?>" id="<?php echo $this->name.'_'.$lang_item; ?>_flag" href="#" onclick="change_form_language_<?php echo $this->name; ?>('<?php echo $this->name; ?>', '<?php echo $this->name.'_'.$lang_item; ?>'); return false;"><img src="<?php echo View::get_media_url('images/languages/'.$lang_item.'.png'); ?>" alt="<?php echo $lang_item; ?>"/></a>&nbsp;
            <?php

        }

        ?>
        </div>
        <hr />
        <script language="Javascript">
            
            function change_form_language_<?php echo $this->name; ?>(field, lang_field)
            {

                if(typeof jQuery == 'undefined') 
                {
                    alert('<?php echo I18n::lang('common', 'cannot_load_jquery', 'Cannot load jquery'); ?>');
                    return false;

                }

                <?php

                foreach(I18n::$arr_i18n as $lang_item)
                {

                    ?>
                    $("#<?php echo $this->name.'_'.$lang_item; ?>").hide();//removeClass("no_hidden_form").addClass("hidden_form");
                    $("#<?php echo $this->name.'_'.$lang_item; ?>_flag").removeClass("choose_flag").addClass("no_choose_flag");
                    <?php

                }

                ?>
                
                lang_field=lang_field.replace('[', '\\[');
                lang_field=lang_field.replace(']', '\\]');

                $("#"+lang_field).show();//.removeClass("hidden_form").addClass("no_hidden_form");
                $("#"+lang_field+'_flag').removeClass("no_choose_flag").addClass("choose_flag");
                
            }

        </script>
        <?php
        
        /*
        ob_start();

        if(gettype($arr_values)!='array')
        {

            $arr_values = @unserialize( $arr_values );
            
            if(gettype($arr_values)!='array')
            {

                $arr_values=array();
                
            }
            
        }
        
        
        foreach(I18n::$arr_i18n as $lang_select)
        {

            $arr_selected[Utils::slugify($lang_select)]='hidden_form';
            $arr_selected[Utils::slugify(I18n::$language)]='no_hidden_form';
            
            settype($arr_values[$lang_select], 'string');
            echo '<div class="'.$arr_selected[Utils::slugify($lang_select)].'" id="'.$this->name.'_'.$lang_select.'">';
            echo $this->type_form($this->name.'['.$lang_select.']', '', $arr_values[$lang_select]);
            echo '</div>';

        }
        ?>
        <div id="languages">
        <?php

        $arr_selected=array();

        foreach(PhangoVar::$arr_i18n as $lang_item)
        {
            //set

            $arr_selected[Utils::slugify($lang_item)]='no_choose_flag';
            $arr_selected[Utils::slugify(I18n::$language)]='choose_flag';

            ?>
            <a class="<?php echo $arr_selected[Utils::slugify($lang_item)]; ?>" id="<?php echo $this->name.'_'.$lang_item; ?>_flag" href="#" onclick="change_form_language_<?php echo $this->name; ?>('<?php echo $this->name; ?>', '<?php echo $this->name.'_'.$lang_item; ?>'); return false;"><img src="<?php echo get_url_image('languages/'.$lang_item.'.png'); ?>" alt="<?php echo $lang_item; ?>"/></a>&nbsp;
            <?php

        }

        ?>
        </div>
        <hr />
        <script language="Javascript">
            
            function change_form_language_<?php echo $this->name; ?>(field, lang_field)
            {

                if(typeof jQuery == 'undefined') 
                {
                    alert('<?php echo PhangoVar::$lang['common']['cannot_load_jquery']; ?>');
                    return false;

                }

                <?php

                foreach(PhangoVar::$arr_i18n as $lang_item)
                {

                    ?>
                    $("#<?php echo $this->name.'_'.$lang_item; ?>").hide();//removeClass("no_hidden_form").addClass("hidden_form");
                    $("#<?php echo $this->name.'_'.$lang_item; ?>_flag").removeClass("choose_flag").addClass("no_choose_flag");
                    <?php

                }

                ?>
                
                lang_field=lang_field.replace('[', '\\[');
                lang_field=lang_field.replace(']', '\\]');

                $("#"+lang_field).show();//.removeClass("hidden_form").addClass("no_hidden_form");
                $("#"+lang_field+'_flag').removeClass("no_choose_flag").addClass("choose_flag");
                
            }

        </script>
        <?php


        $text_form=ob_get_contents();

        ob_end_clean();

        return $text_form;
        */
    }
    
    function setform($value)
    {
        
        if(!gettype($value)=='array')
        {

            settype($arr_value, 'array');

            $arr_value = @unserialize( $value );
            
            return $arr_value;

        }
        else
        {

            return $value;

        }

    }

}

?>