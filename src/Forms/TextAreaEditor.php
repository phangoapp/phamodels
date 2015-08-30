<?php

namespace PhangoApp\PhaModels\Forms;

use PhangoApp\PhaModels\Forms\BaseForm;
use PhangoApp\PhaUtils\Utils;
use PhangoApp\PhaView\View;
use PhangoApp\PhaRouter\Routes;
use PhangoApp\PhaI18n\I18n;

class TextAreaEditor extends BaseForm {
    
    public function form()
    {
    
        //PhangoVar::$arr_cache_jscript[]='tinymce_path.js';
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
                    "table contextmenu directionality emoticons template paste textcolor"
                ],
                file_browser_callback: function(field_name, url, type, win){
                            var filebrowser = "<?php echo Routes::make_module_url('gallery', 'index'); ?>";
                            tinymce.activeEditor.windowManager.open({
                            title : "<?php echo I18n::lang('common', 'load_file', 'Load_image'); ?>",
                            width : 520,
                            height : 400,
                            url : filebrowser
                            }, {
                            window : win,
                            input : field_name
                            });
                            return false;
                            }

                });
                
                

            });

            </script>

            <?php

            View::$header['tinymce']=ob_get_contents();

            ob_end_clean();
        
        }
        
        ?>
        <p><textarea class="tinymce_editor" name="<?php echo $this->name; ?>"><?php echo $this->default_value; ?></textarea></p>
        <?php
    
    }
}

?>