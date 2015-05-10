<?php

namespace PhangoApp\PhaModels;

/**
* A simple class for create where strings with checking.
*
* With this extension, you can create sql strings for use on where parameter of select method from Webmodel.
*
* Example ['AND']->array( 'field' => array('!=', 25), 'field2' => array('=', 'value_field'), 'field3' => array('LIKE', 'value_field'), 'field4' => array('IN',  array('1','2','3'), 'limit_sql' => array('LIMIT', array(1, 10), 'order_by' => array('order_fieldY', 'ASC'
))
* 
*You can join differents sql sentences 
*
* @warning Phango developers recommend use Webmodel::check_where_sql method on a simple sql string
*
*/

class WhereSql {

	public $initial_sql;
	public $arr_conditions;
	public $order_by;
	public $limit;

	public function __construct($model_name, $arr_conditions=array(), $order_by=array(), $limit=array())
	{
	
		$this->model_name=$model_name;
		$this->initial_sql='WHERE 1=1 ';
		$this->arr_conditions=$arr_conditions;
		$this->order_by=$order_by;
		$this->limit=$limit;
	
	}
	
	public function get_where_sql()
	{
	
		$arr_to_glued=array();
		
		$arr_define_sql=array();
		
		$arr_final_sql=array();
		
		$first_sep=0;
		
		foreach($this->arr_conditions as $group => $arr_glue)
		{
		
			foreach($arr_glue as $glue => $arr_elements)
			{
				
				foreach($arr_elements as $arr_fields_where)
				{
					
					foreach($arr_fields_where as $field => $operation)
					{
					
						
					
						list($field_select, $model_name, $field_name)=$this->set_safe_name_field(Webmodel::$model[$this->model_name], $field);
								
						$op=$operation[0];
						
						$value=$operation[1];
						
						switch($op)
						{
						
							case '=':
							
								$value=Webmodel::$model[$model_name]->components[$field_name]->simple_check($value);
							
								$arr_to_glued[]=$field_select.' '.$op.' \''.$value.'\'';
							
							break;
							
							case '!=':
								
								$value=Webmodel::$model[$model_name]->components[$field_name]->simple_check($value);
								
								$arr_to_glued[]=$field_select.' '.$op.' \''.$value.'\'';
							
							break;
							
							case 'LIKE':
							
								$value=Webmodel::$model[$model_name]->components[$field_name]->simple_check($value);
							
								$arr_to_glued[]=$field_select.' '.$op.' \''.$value.'\'';
							
							break;
							
							case 'IN':
							case 'NOT IN':
							
								foreach($value as $key_val => $val)
								{
								
									$value[$key_val]=Webmodel::$model[$model_name]->components[$field_name]->check($val);
								
								}
								
								$arr_to_glued[]=$field_select.' '.$op.' (\''.implode('\',\'', $value).'\')';
							
							break;
						
						}
						
					}
				
				}
				
				$arr_define_sql[$glue]=' '.implode(' '.$glue.' ', $arr_to_glued).' ';
				
				$arr_to_glued=array();
			
			}
			
			$arr_group=explode('_', $group);
			
			$separator_group=end($arr_group);
			
			if($first_sep==0)
			{
			
				$separator_group='AND';
			
			}
			
			$first_sep++;
			
			$arr_final_sql[]=$separator_group.' ( '.implode(' '.$glue.' ', $arr_define_sql).' ) ';
			
			//$arr_define_sql=array();
		
		}
		
		$this->initial_sql.=implode('', $arr_final_sql);
		
		return $this->initial_sql;
	
	}
	
	public function get_order_sql()
	{
	
		$arr_order_final=array();
		
		$final_order='';
		
		//$order_by[]=array('field' => 'moderator', 'order' => 'ASC'
		
		if(count($this->order_by)>0)
		{
			foreach($this->order_by as $arr_order)
			{
			
				list($field_select, $model_name, $field_name)=$this->set_safe_name_field(Webmodel::$model[$this->model_name], $arr_order['field']);
			
				$arr_order_final[]=$field_name.' '.$arr_order['order'];
			
			}
		
			$final_order=' ORDER BY '.implode(' ,', $arr_order_final);
		
		}
		
		return $final_order;
	
	}
	
	public function get_limit_sql()
	{
			
		if(count($this->limit)>0)
		{
		
			return ' LIMIT '.implode(' ,', $this->limit);
		
		}
		
		return '';
	
	}
	
	public function get_all_sql()
	{
	
		return $this->get_where_sql().$this->get_order_sql().$this->get_limit_sql();
	
	}
	
	public function set_safe_name_field($class, $field)
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

}

