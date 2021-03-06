<?php

/**
*
* @author  Antonio de la Rosa <webmaster@web-t-sys.com>
* @file
* @package PhaModels
*
*
*/

/**
* Extra Method for Webmodel. Is used for check if element with $idrow id exists in the database.
*
* @param Webmodel $class Webmodel instance class.
* @param mixed $idrow The id of the element to search.
* @param string $field_search The field where search the specified "id" if you want use other field distinct to default id field.
*
*/

function element_exists_method_class($class, $idrow, $field_search='')
{

	if($field_search=='')
	{
	
		$field_search=$class->idmodel;
	
	}

	settype($idrow, 'integer');
	
	$class->set_conditions('where '.$field_search.'=\''.$idrow.'\'');
	
	$num_elements=$class->select_count($class->idmodel);
	
	return $num_elements;

}

?>