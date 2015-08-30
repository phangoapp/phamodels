<?php

namespace PhangoApp\PhaModels\Forms;

use PhangoApp\PhaI18n\I18n;
use PhangoApp\PhaUtils\Utils;
use PhangoApp\PhaModels\Forms\BaseForm;
use PhangoApp\PhaView\View;

class MultiLangForm extends BaseForm{

    public $type_form;

    public function __construct($name, $value)
    {
    
        $this->type_form=new BaseForm($name, $value);
    
        parent::__construct($name, $value);
    
    }
    
    public function form()
    {
    
        //make a foreach with all langs
        //default, es_ES, en_US, show default if no exists translation for selected language.
        /*
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
        
        if(!get_class($this->type_form))
        {
        
            throw new \Exception('Error: need set the $type_form property with a valid class form in '.$this->name);
        
        }
        
        if(gettype($this->default_value)!='array')
        {
        
            $arr_values=unserialize($this->default_value);
            
        }
        else
        {
        
            $arr_values=$this->default_value;
        
        }
        
        //print_r($this->default_value);
        
        foreach(I18n::$arr_i18n as $lang_select)
        {
        
            $slug=Utils::slugify($lang_select);
            $lang_slug=Utils::slugify(I18n::$language);
        
            $arr_selected[$slug]='hidden_form';
            $arr_selected[$lang_slug]='no_hidden_form';
            
            $this->type_form->name=$this->name.'['.$lang_select.']';
            
            $this->type_form->default_value=$this->setform($arr_values[$lang_select]);
            
            echo '<div class="'.$arr_selected[$slug].' '.$this->name.'_group" id="'.$this->name.'_'.$lang_select.'">';
            echo $this->type_form->form();
            echo '</div>';
        
        }
        
        ?>
         <div id="languages">
        <?php
        
        $arr_selected=array();

        $language=Utils::slugify(I18n::$language);
        
        foreach(I18n::$arr_i18n as $lang_item)
        {
            //set
            
            $lang_item_slug=Utils::slugify($lang_item);
            
            $arr_selected[$lang_item_slug]='no_choose_flag';
            $arr_selected[$language]='choose_flag';

            ?>
            <a class="flag <?php echo $arr_selected[$lang_item_slug]; ?> <?php echo $this->name; ?>_flag" alt="<?php echo $this->name; ?>" id="<?php echo $lang_item; ?>_flag"  href="#"><img src="<?php echo View::get_media_url('images/languages/'.$lang_item.'.png'); ?>" alt="<?php echo $lang_item; ?>"/></a>
            <?php

        }

        ?>
        </div>
        <br />
        <hr />
        <?php
        
    }
    
    static public function header()
    {
    
        ob_start();
    
        ?>
        <script language="Javascript">
        $(document).ready( function () {
        
            $('.flag').click( function () {
                
                if( $(this).hasClass('no_choose_flag') )
                {
                    group=$(this).attr('alt');
                    
                    lang=$(this).attr('id').replace('_flag', '');
                
                    flag='.'+group+'_flag';
                    
                    show_input='#'+group+'_'+lang;
                    
                    remove_input='.'+group+'_group'
                
                    //Change flags
                
                    $(flag).removeClass('choose_flag').addClass('no_choose_flag');
                    
                    $(this).removeClass('no_choose_flag').addClass('choose_flag');
                    
                    //Change inputs
                    
                    $(remove_input).removeClass('no_hidden_form').addClass('hidden_form');
                    
                    $(show_input).removeClass('hidden_form').addClass('no_hidden_form');
                
                }
                
                return false;
            
            });
        
        });
        </script>
        <?php
        
        View::$header[]=ob_get_contents();
        
        ob_end_clean();
    
    }

}

?>