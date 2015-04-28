<?php

/**
*
* @author  Antonio de la Rosa <webmaster@web-t-sys.com>
* @file
* @package Core/ModelExtraMethods
*
*
*/

/**
* Deprecated method used for create admin sites based on a model
*
* @deprecated Now, only is a hook of GenerateAdminClass
*/

function generate_admin_method_class($class, $arr_fields, $arr_fields_edit, $url_options, $options_func='BasicOptionsListModel', $where_sql='', $arr_fields_form=array(), $type_list='Basic', $no_search=false)
{

	/*load_libraries(array('generate_admin_ng'));
	
	generate_admin_model_ng($class->name, $arr_fields, $arr_fields_edit, $url_options, $options_func, $where_sql, $arr_fields_form, $type_list, $no_search);*/
	
	load_libraries(array('admin/generate_admin_class'));
	
	$admin=new GenerateAdminClass($class->name);
	
	$admin->arr_fields=$arr_fields;
	
	$admin->arr_fields_edit=$arr_fields_edit;
	
	$admin->set_url_post($url_options);
	
	$admin->options_func=$options_func;
	
	$admin->where_sql=$where_sql;
	
	$admin->no_search=$no_search;
	
	$admin->show();

}

?>