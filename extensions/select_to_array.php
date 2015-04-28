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
* A useful method for Webmodel for load an array of rows resulted from a query.
* 
* With this method you can load only a row specifyng the model id value using a where sql statement. 
*
* @param Webmodel $class The instance of the class used
* @param string $where A where sql statement.
* @param array $arr_select An array where the values are the correspondent fields of the model
* @param boolean $raw_query If true, ForeignKeys will be ignored, if false, the return value will load the relationships specified.
* @param integer $index_id If 0, return only associatives keys, if 1, return numeric keys.
*
*/

function select_to_array_method_class($class, $where="", $arr_select=array(), $raw_query=0, $index_id='')
{

	$arr_return=array();
	
	if($index_id=='')
	{
	
		$index_id=$class->idmodel;
	
	}
	
	$arr_select[]=$index_id;
	
	if(count($arr_select)==1)
	{
	
		$arr_select=$class->all_fields();
	
	}
	
	$query=$class->select($where, $arr_select, $raw_query);
	
	while($arr_row=webtsys_fetch_array($query))
	{
	
		
		$arr_return[$arr_row[$index_id]]=$arr_row;
			
		
	}
	
	return $arr_return;

}

?>