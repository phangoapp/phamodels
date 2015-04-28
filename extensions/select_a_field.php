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
* Extension method class used for select a field only of the model in the db
*
* This method is useful if you only want load a field of a model.
*
* @param Webmodel $class The model used for search in
* @param string $where A where sql statement.
* @param string $field The field where search.
*/

function select_a_field_method_class($class, $where, $field)
{
	
	$arr_field=array();
	
	$query=$class->select($where, array($field), $raw_query=1);
	
	while(list($field_choose)=webtsys_fetch_row($query))
	{
	
		$arr_field[]=$field_choose;
	
	}
	
	return $arr_field;

}

?>