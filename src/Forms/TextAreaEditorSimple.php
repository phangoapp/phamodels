<?php

namespace PhangoApp\PhaModels\Forms;

use PhangoApp\PhaModels\Forms\BaseForm;
use PhangoApp\PhaUtils\Utils;
use PhangoApp\PhaView\View;
use PhangoApp\PhaRouter\Routes;
use PhangoApp\PhaI18n\I18n;

class TextAreaEditorSimple extends BaseForm {
    
    public $load_image_url='';
    
    public function __construct($name, $value, $load_image_url='')
    {
        
        parent::__construct($name, $value);
        $this->load_image_url=$load_image_url;
        
    }
    
    public function form()
    {
    
        //PhangoVar::$arr_cache_jscript[]='tinymce_path.js';
        
        if(!isset(View::$header['ckeditor']))
        {
            
            View::$js[]='jquery.min.js';
            View::$js[]='ckeditor/ckeditor.js';
            
            ob_start();
            
            ?>
            <script>
            $(document).ready( function () {
                
               CKEDITOR.config.toolbar = [
                        { name: 'document', items: [ 'Source', '-','Preview'] },
                        { name: 'clipboard', items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
                        { name: 'editing', items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },
                    
                        { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline' ] },
                        
                        { name: 'about', items: [ 'About' ] }
                    ];
               
            });
            </script>
            <?php
            
            View::$header['ckeditor']=ob_get_contents();

            ob_end_clean();
            
        }
        
        /*
        if(!isset(View::$header['tinymce']))
        {
            View::$js[]='jquery.min.js';
            View::$js[]='tinymce/tinymce.min.js';
            
            ob_start();
            
            ?>
            
            <script type="text/javascript">
            
            
            $(document).ready( function () {
                
                
                tinymce.init({
                selector: "textarea.tinymce_editor",

                //theme: "modern",
                height: 300,
                plugins: [
                    "advlist autolink link image lists charmap print preview hr anchor pagebreak",
                    "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                    "table contextmenu directionality emoticons template paste textcolor code"
                ],
                
                relative_urls : false,
/
                image_list: "<?php echo $this->load_image_url; ?>"
                /*
                file_browser_callback: function(field_name, url, type, win){
                            var filebrowser = "<?php echo $this->load_image_url; ?>";
                            tinymce.activeEditor.windowManager.open({
                            title : "<?php echo I18n::lang('common', 'load_file', 'Load image'); ?>",
                            width : 520,
                            height : 400,
                            url : filebrowser
                            }, {
                            window : win,
                            input : field_name
                            });
                            return false;
                            }*/

               /* });
                
                

            });

            </script>

            <?php

            View::$header['tinymce']=ob_get_contents();

            ob_end_clean();
        
        }
        */
        ?>
        <p><textarea class="ckeditor" name="<?php echo $this->name; ?>" id="<?php echo $this->name; ?>_form_field"><?php echo $this->default_value; ?></textarea></p>
        <?php
    
    }
}

?>
