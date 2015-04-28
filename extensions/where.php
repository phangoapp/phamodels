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
* A simple extension for create where strings with checking.
*
* With this extension, you can create sql strings for use on where parameter of select method from Webmodel.
*
* Example ['AND']->array( 'field' => array('!=', 25), 'field2' => array('=', 'value_field'), 'field3' => array('LIKE', 'value_field'), 'field4' => array('IN',  array('1','2','3'), 'limit_sql' => array('LIMIT', array(1, 10), 'order_by' => array('order_fieldY', 'ASC'
))
* 
* You can join differents sql sentences 
* 
* @warning Experimental, don't use in production
*/

function where_method_class($class, $arr_where, $initial_sql='WHERE', $parenthesis=0, $order_by=array(), $limit=array())
{

	$arr_to_glued=array();
	
	$glue=key($arr_where);

	/*foreach($arr_where as $glue => $arr_fields_where)
	{*/
	
		foreach($arr_where[$glue] as $field => $operation)
		{
			
			list($field_select, $model_name, $field_name)=set_safe_name_field($class, $field);
						
			$op=$operation[0];
			
			$value=$operation[1];
			
			switch($op)
			{
			
				case '=':
				
					$value=PhangoVar::$model[$model_name]->components[$field_name]->check($value);
				
					$arr_to_glued[]=$field_select.' '.$op.' \''.$value.'\'';
				
				break;
				
				case '!=':
				
					$value=PhangoVar::$model[$model_name]->components[$field_name]->check($value);
				
					$arr_to_glued[]=$field_select.' '.$op.' \''.$value.'\'';
				
				break;
				
				case 'LIKE':
				
					$value=PhangoVar::$model[$model_name]->components[$field_name]->check($value);
				
					$arr_to_glued[]=$field_select.' '.$op.' \''.$value.'\'';
				
				break;
				
				case 'IN':
				case 'NOT IN':
				
					foreach($value as $key_val => $val)
					{
					
						$value[$key_val]=PhangoVar::$model[$model_name]->components[$field_name]->check($val);
					
					}
					
					$arr_to_glued[]=$field_select.' '.$op.' (\''.implode('\',\'', $value).'\')';
				
				break;
			
			}
			

		
		}
	
	//}
	
	$initial_sql.=implode(' '.$glue.' ', $arr_to_glued);
	
	if(count($order_by)>0)
	{
	
		$initial_sql.='';
	
	}
	
	return $initial_sql;
	
}

/**
* internal function for where_method_class
*/

function set_safe_name_field($class, $field)
{
	
	$pos_dot=strpos($field, '.');
	
	$model_name='';
	$field_name='';
	
	if($pos_dot!==false)
	{
	
		//The model need to be loading previously.
		
		//substr ( string $string , int $start [, int $length ] )
		
		$model_name=substr($field, 0, $pos_dot);
		$field_name=substr($field, $pos_dot+1);
		
		$field_select='`'.$model_name.'`.`'.$field_name.'`';
	
	}
	else
	{
		
		$model_name=$class->name;
		$field_name=$field;
		
		$field_select='`'.$class->name.'`.`'.$field.'`';
		
	}
	
	return array($field_select, $model_name, $field_name);

}

?>