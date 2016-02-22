<?php

namespace PhangoApp\PhaModels\Forms;

use PhangoApp\PhaI18n\I18n;
use PhangoApp\PhaUtils\Utils;
use PhangoApp\PhaTime;

class DateForm extends \PhangoApp\PhaModels\Forms\BaseForm {

    public $set_time=1;
    public $see_title=0;

    function form()
    {
    
        $value=$this->default_value;

        settype($value, 'string');
        
        if($value=='')
        {

            $day='';
            $month='';
            $year='';
            $hour='';
            $minute='';
            $second='';

        }
        else
        {
        
            $value=PhaTime\DateTime::gmt_to_local($value);
                        
            list($year, $month, $day, $hour, $minute, $second)=PhaTime\DateTime::format_timedata($value);
            
        }
        
        //return '<input type="'.$this->type.'" class="'.$this->css.'" name="'.$this->name.'" value="'.$this->setform($this->default_value).'">';
        
        $date='<span id="'.$this->name.'_field_form" class="'.$this->css.'">';
        
        $date.='<input type="text" name="'.$this->name.'[]" value="'.$day.'" size="2" maxlength="2"/>'."\n";
        $date.='<input type="text" name="'.$this->name.'[]" value="'.$month.'" size="2" maxlength="2"/>'."\n";
        $date.='<input type="text" name="'.$this->name.'[]" value="'.$year.'" size="4" maxlength="4"/>'."\n&nbsp;&nbsp;&nbsp;";
        
        if($this->set_time==1)
        {
            $hour_txt=I18n::lang('common', 'hour', 'Hour');
            $minute_txt=I18n::lang('common', 'minute', 'Minute');
            $second_txt=I18n::lang('common', 'second', 'Second');
            
            if($this->see_title==0)
            {
            
                $hour_txt='';
                $minute_txt='';
                $second_txt='';
            
            }

            $date.=$hour_txt.' <input type="text" name="'.$this->name.'[]" value="'.$hour.'" size="2" maxlength="2" />'."\n";
            $date.=$minute_txt.' <input type="text" name="'.$this->name.'[]" value="'.$minute.'" size="2" maxlength="2" />'."\n";
            $date.=$second_txt.' <input type="text" name="'.$this->name.'[]" value="'.$second.'" size="2" maxlength="2" />'."\n";

        }
        

        echo '</span>';
        
        return $date;

    }
   
}

?>