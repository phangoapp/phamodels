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
* A useful method for Webmodel for load a row from a db model (table)
* 
* With this method you can load only a row specifyng the model id value. 
*
* @param Webmodel $class The instance of the class used
* @param integer $idrow The id value of the row.
* @param array $arr_select An array where the values are the correspondent fields of the model
* @param boolean $raw_query If true, ForeignKeys will be ignored, if false, the return value will load the relationships specified.
* @param integer $assoc If 0, return only associatives keys, if 1, return numeric keys.
*
*/

function select_a_row_method_class($class, $idrow, $arr_select=array(), $raw_query=0, $assoc=0)
{

	settype($idrow, 'integer');
	
	$query=$class->select('where '.$class->name.'.`'.$class->idmodel.'`=\''.$idrow.'\'', $arr_select, $raw_query);
	
	return webtsys_fetch_array($query, $assoc);

}

?>