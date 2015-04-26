<?php

namespace PhangoApp\PhaModels;

use PhangoApp\PhaModels\CoreFields;

/**
* The most important class for the framework
*
* Webmodel is a class for create objects that represent models. This models are a mirage of SQL tables. You can create fields, add indexes, foreign keys, and more.
*
*
*/

class Webmodel {

	
	/**
	* folder containing the models
	*/
	
	static public $model_path='app/models';

	/**
	* Database hosts array.
	*/
	
	static public $host_db=array();

	/**
	* Database hosts array.
	*/
	
	static public $db=array();
	
	static public $login_db=array();
	
	static public $pass_db=array();
	
	static public $type_db='MySQLClass';
	
	/**
	* Connection to db.
	*/
	
	static public $connection=array();
	
	/**
	* Variable for cache the connections.
	*/

	static public $connection_func=array();
	
	/**
	* Array where the select_db's are saved.
	*
	*/
	
	static public $select_db=array();
	
	/**
	* String where save the query if error.
	*/
	
	static public $save_query=array();
	
	/**
	* Variable for the db prefix. For security, change this always.
	*/
	
	static public $prefix_db='';

	/**
	*
	* With this property, you can define what is the server connection that you have to use for read the source data.
	* If you create a phango loader that balancer where you read the data, you can obtain many flexibility.
	* You can define how table related with a server for example.
	*
	*/

	public $db_selected='default';
	
	/**
	* The name of the model.
	*/
	
	public $name;
	
	/**
	* A identifier used for show the name of model for humans.
	*/
	
	public $label;
	
	/**
	* The name of key field of the model.
	*/
	
	public $idmodel;

	/**
	* An array where objects of the PhangoField class are saved. This objects are needed for create fields on the table and each of these represents a field on db table.
	*/

	public $components;
	
	/**
	*
	* An array where objects of the ModelForm class are saved. This objects are needed for create html forms based in the models.
	*
	*/
	
	public $forms;

	//Components is a array for the fields forms of this model

	//This variables define differents functions for use in automatize functions how generate_admin
	//I prefer this method instead of overloading function methods
	
	/**
	*
	* An string for use on internal tasks of generate automatic admin.
	*
	*/

	public $func_update='basic';

	/**
	* In this variable is store errors using the model...
	*/
	
	public $std_error='';

	/**
	* Variable for indicate to forms that this model have enctype...
	*/
	
	public $enctype='';
	
	/**
	* Array used for inverse foreign keys.
	*
	* This array is used when you need access to a model with a foreignkey key related with its. 
	* Example: array($key1 => array($field_connection, $field1, $field2 ....)) where key is the model name with related foreignkey, and the first element of array for the element is the connection (tipically a foreignkeyfield).
	*/
	
	public $related_models=array();
	
	/**
	* An array where the model save the name of the related models via ForeignKeyField. You need use $this->register method for fill this array.
	*/
	
	public $related_models_delete=array();
	
	/**
	*
	* If you checked the values that you going to save on your model, please, put this value to 1 or true.
	*
	*/
	
	public $prev_check=0;
	
	/**
	*
	* Property for set if the next select query have a DISTINCT sentence.
	*
	*/
	
	public $distinct=0;
	
	/**
	* Property for save the required fields where you use reset_require method
	* 
	*/
	
	public $save_required=array();
	
	/**
	* Property for select the fields for update or insert
	* 
	*/
	
	public $arr_fields_updated=array();
	
	/**
	* Property that define if this model is cached or not.
	*
	*/
	
	public $cache=0;
	
	/**
	* Property that define if this model was cached before, if not, obtain the query from the sql db.
	*
	*/
	
	public $cached=0;
	
	/**
	* Property that define the cache type, nosql, cached in memory with memcached or redis, etc.
	*
	*/
	
	public $type_cache='';

	/**
	* Property that define if id is modified.
	*
	*/
	
	public $modify_id=0;
	
	//Construct the model

	/**
	* Basic constructor for model class.
	*
	* Phango is a MVC Framework. The base of a MVC framework are the models. A Model is a representation of a database table and are used for create, update and delete information. With the constructor your initialize variables how the name of model, 
	*
	* @param string $name_model is the name of the model
	* 
	* 
	*/
	
	public function __construct($name_model)
	{
	
		//Webmodel::$root_model='app/'.$name_model.'/'.Webmodel::$model_path;

		$this->name=$name_model;
		$this->idmodel='Id'.ucfirst($this->name);
		$this->components[$this->idmodel]=new CoreFields\PrimaryField();
		$this->label=$this->name;
		
		if(!isset(Webmodel::$connection_func[$this->db_selected]))
		{
		
			Webmodel::$connection_func[$this->db_selected]='connect_to_db';
		
		}

	}
	
	/**
	* Method for load config from a project.
	*/
	
	static public function load_config()
	{
	
		//if(is_file($this->path_mo
	
	}
	
	/**
	* Method for load models from a project.
	*
	* @param string $model Model name. The model is search in Webmodel::$model_path and the name is $model.php
	*
	*/
	
	static public function load_model($model)
	{
	
		if(is_file(Webmodel::$model_path.'/'.$model.'.php'))
		{
		
			include(Webmodel::$model_path.'/'.$model.'.php');
			
			$func_load=$model.'Load';
			
			if(!function_exists($func_load))
			{
			
				throw new \Exception('Error: function '.$func_load.' not found in '.Webmodel::$model_path.'/'.$model.'.php');
			
			}
			else
			{
			
				return $func_load();
				
			}
		
		}
		else
		{
		
			throw new \Exception('Error: model not found in '.Webmodel::$model_path.'/'.$model.'.php');
		
		}
	
	}
	
	/**
	* Method for connect to the db
	*
	*/
	
	public function connect_to_db()
	{
		
		include(__DIR__.'/Databases/'.Webmodel::$type_db.'.php');
		//load_libraries(array('database/'.Webmodel::$type_db), Webmodel::$base_path);
	
		if(!MySQLClass::webtsys_connect( Webmodel::$host_db[$this->db_selected], Webmodel::$login_db[$this->db_selected], Webmodel::$pass_db[$this->db_selected] , $this->db_selected))
		{
		
			$output=ob_get_contents();
			
			ob_clean();

			//$text_error='<p>Output: '.$output.'</p>';

			/*$arr_error_sql[0]='<p>Error: Cannot connect to MySQL db.</p>';    
			$arr_error_sql[1]='<p>Error: Cannot connect to MySQL db, '.$output.'</p>';*/
		
			//show_error($arr_error_sql[0], $arr_error_sql[1]);
			
			throw new \Exception('Error: cannot connect to database');
		
		}

		Webmodel::$select_db[$this->db_selected]=MySQLClass::webtsys_select_db( Webmodel::$db[$this->db_selected] , $this->db_selected);
		
		if(Webmodel::$select_db[$this->db_selected]!=false && Webmodel::$connection[$this->db_selected]!=false)
		{
			
			Webmodel::$connection_func[$this->db_selected]='dummy_connect_to_db';
			
		}
		else
		{
		
			$output=ob_get_contents();
			
			ob_clean();

			//$text_error='<p>Output: '.$output.'</p>';

			/*$arr_error_sql[0]='<p>Error: Cannot connect to MySQL db.</p>';    
			$arr_error_sql[1]='<p>Error: Cannot connect to MySQL db, '.$output.'</p>';
		
			show_error($arr_error_sql[0], $arr_error_sql[1]);*/
			
			throw new \Exception('Error: cannot connect to database');
		
		}
		
	
	}
	
	/**
	* Dummy function for save an if by query.
	*
	*/
	
	public function dummy_connect_to_db()
	{
	
		
	
	}
	
	/**
	* Method used for connet to db, if you are connected, execute a dummy db connection method.
	* 
	*/
	
	public function set_phango_connection()
	{
		
		$method_connection=Webmodel::$connection_func[$this->db_selected];
		
		$this->$method_connection();
		
	}
	
	/**
	* A method for change the name of the id field.
	* 
	* Id Field is the field that in the database is used how basic identifier. By default, this name is Id.ucfirst($this->name) but you can change its name with this method after you have declared a new model instance.
	*
	* @param string $name_id is the name of the id field.
	*/

	public function change_id_default($name_id)
	{

		//Check if i create more components, if create more, die.

		if(count($this->components)>1)
		{

			//show_error('<p>Error in a model for use ids.</p>', '<p>Error in model '.$this->name.' for use change_id_default. This method must be used before any component.</p>');
			
			throw new \Exception('Error in model '.$this->name.' for use change_id_default. This method must be used before any component');

		}
		
		unset($this->components[$this->idmodel]);
		$this->idmodel=$name_id;
		$this->components[$this->idmodel]=new PrimaryField();

	}
	
	/**
	*
	* A method for connect to the db.
	*
	*
	*/

	//This method insert a row in database using model data

	//Method for create a new row in the model.
	//@param $post is a array where each key is referred to a model field. 
	
	/**
	* This method prepare the new sql query
	*
	* @warning This method don't check value of $fields. Use $this->check_all for this task.
	*
	* @param array $fields Is an array with data to insert. You have a key that represent the name of field to fill with data, and the value that is the data for fill.
	*
	*/
	
	public function prepare_insert_sql($fields)
	{
	
		//Foreach for create the query that comes from the $post array
			
		foreach($fields as $key => $field)
		{
		
			$quot_open=$this->components[$key]->quot_open;
			$quot_close=$this->components[$key]->quot_close;
		
			if(get_class($this->components[$key])=='ForeignKeyField' && $fields[$key]==NULL)
			{
			
				
				$quot_open='';
				$quot_close='';
				
				if($this->components[$key]->yes_zero==0)
				{
					$fields[$key]='NULL';
				}
			}
		
			$arr_fields[]=$quot_open.$fields[$key].$quot_close;
		
		}
			
		return 'insert into '.$this->name.' (`'.implode("`, `", array_keys($fields)).'`) VALUES ('.implode(", ",$arr_fields).') ';
	
	}
	
	/**
	* This method insert a row in database using model how mirage of table.
	* 
	* On a db, you need insert data. If you have created a model that reflect a sql table struct, with this method you can insert new rows easily without write sql directly.
	*
	* @param array $post Is an array with data to insert. You have a key that represent the name of field to fill with data, and the value that is the data for fill.
	*/

	public function insert($post)
	{
	
		$this->set_phango_connection();
		
		//Make conversion from post
		
		$post=$this->unset_no_required($post);
		
		//Check if minimal fields are fill and if fields exists in components.Check field's values.
		
		if(!$this->modify_id)
		{
		
			unset($post[$this->idmodel]);
			
		}
		
		$arr_fields=array();
		
		if( $fields=$this->check_all($post) )
		{	
		
			if( !( $query=MySQLClass::webtsys_query($this->prepare_insert_sql($fields), $this->db_selected) ) )
			{
			
				$this->std_error.=Webmodel::$l_['error_model']->lang('cant_insert', 'Can\'t insert').' ';
				return 0;
			
			}
			else
			{
			
				return 1;
				
			}
		}
		else
		{	
			
			$this->std_error.=Webmodel::$l_['error_model']->lang('cant_insert', 'Can\'t insert').' ';

			return 0;

		}
		
	}

	//Method update a row in database using model data
	//@param $post is a array where each key is referred to a model field. 
	//@param $conditions is a sql sentence for specific conditions for the query Example: "where id=2"
	
	/**
	* This method update rows from a database using model how mirage of table.
	* 
	* If you have inserted a row, you'll need update in the future, with this method you can update your row.
	*
	* @param $post Is an array with data to update. You have a key that represent the name of field to fill with data, and the value that is the data for fill.
	* @param $conditions is a string containing a sql string beginning by "where". Example: where id=1.
	*/
	
	public function update($post, $conditions="")
	{
	
		$this->set_phango_connection();
		
		//Make conversion from post

		//Check if minimal fields are fill and if fields exists in components.

		$arr_fields=array();

		//Unset the id field from the model for security
		
		if(!$this->modify_id)
		{
		
			unset($post[$this->idmodel]);
			
		}
		
		$post=$this->unset_no_required($post);
		
		//Checking and sanitizing data from $post array for use in the query
		
		if( $fields=$this->check_all($post) )
		{
			
			//Foreach for create the query that comes from the $post array
			
			foreach($this->components as $key => $component)
			{
				if(isset($fields[$key]))
				{
				
					$quot_open=$component->quot_open;
					$quot_close=$component->quot_close;
				
					if(get_class($component)=='ForeignKeyField' && $fields[$key]==NULL)
					{
					
						$quot_open='';
						$quot_close='';
						$fields[$key]='NULL';
					
					}
				
					$arr_fields[]='`'.$key.'`='.$quot_open.$fields[$key].$quot_close;
					
				}
	
			}
			
			//Load method for checks the values on database directly. PhangoFields how ParentField, need this for don't create circular dependencies.
		
			/*foreach($this->components as $name_field => $component)
			{*/
			
			foreach($fields as $name_field => $val_field)
			{
				
				if(method_exists($this->components[$name_field],  'process_update_field'))
				{
					;
					if(!$this->components[$name_field]->process_update_field($this, $name_field, $conditions, $fields[$name_field]))
					{
						
						$this->std_error.=Webmodel::$l_['error_model']->lang('cant_update', 'Can\'t update').' ';

						return 0;
					
					}
				
				}
			
			}

			//Create the query..
		
			if(!($query=MySQLClass::webtsys_query('update '.$this->name.' set '.implode(', ' , $arr_fields).' '.$conditions, $this->db_selected) ) )
			{
				
				$this->std_error.=Webmodel::$l_['error_model']->lang('cant_update', 'Can\'t update').' ';
				return 0;
			
			}
			else
			{
			
				return 1;
			
			}
		}
		else
		{
			//Validation of $post fail, add error to $model->std_error
			
			$this->std_error.=Webmodel::$l_['error_model']->lang('cant_update', 'Can\'t update').' ';

			return 0;

		}

	}

	//This method select a row in database using model data
	//You have use MySQLClass::webtsys_fetch_row or alternatives for obtain data
	//Conditions are sql lang, more simple, more kiss
	
	/**
	* This method is a primitive for select rows from a model that represent a table of a database.
	* 
	* If you have inserted a row, you'll need update in the future, with this method you can update your row.
	*
	* You can select rows with sql joins if you add a foreignkey field on $arr_select.
	*
	* @param $conditions is a string containing a sql string beginning by "where". Example: where id=1.
	* @param $arr_select is an array contain the selected fields of the model for obtain. If is not set, all fields are selected.
	* @param $raw_query If set to 0, you obtain fields from table related if you selected a foreignkey field, if set to 1, you obtain an array without any join.
	*/

	public function select($conditions="", $arr_select=array(), $raw_query=0)
	{
		//Check conditions.., script must check, i can't make all things!, i am not a machine!

		$this->set_phango_connection();
		
		if(count($arr_select)==0)
		{
		
			$arr_select=array_keys($this->components);
			

		}
		else
		{
			
			$arr_select=array_intersect($arr_select, array_keys($this->components));

		}

		//$arr_extra_select is an hash for extra fields from related models
		$arr_extra_select=array();
		//$arr_model is an array where are stored the tables used in the query, it is usually only referred to the model table
		$arr_model=array($this->name);
		//$arr_where is an array where is stored the relationship between models
		$arr_where=array('1=1');
		
		$arr_extra_model=array();

		foreach($arr_select as $key => $my_field)
		{
			//Check if field is a key from a related_model

			$arr_select[$key]=$this->name.'.`'.$my_field.'`';

			//Check if a field link with other field from another table...

			//list($arr_select, $arr_extra_select, $arr_model, $arr_where)=$this->recursive_fields_select($key, $this->name, $my_field, $raw_query, $arr_select, $arr_extra_select, $arr_model, $arr_where);
			if(get_class($this->components[$my_field])=='ForeignKeyField')
			{
			
				$arr_extra_model[$key]=$my_field; //$this->components[$my_field]->related_model;
			
			}
			
		}
		
		if($raw_query==0)
		{
		
			//Add fields defined on fields_related_model.
			
			foreach($arr_extra_model as $key => $my_field)
			{
			
				$model_name_related=$this->components[$my_field]->related_model;
				
				//Set the value for the component foreignkeyfield if name_field_to_field is set.
			
				if($this->components[$my_field]->name_field_to_field!='')
				{
				
					$arr_select[$key]=$model_name_related.'.`'.$this->components[$my_field]->name_field_to_field.'` as `'.$my_field.'`';
					
				}
				
				//Set the new fields added for related model...
				
				foreach($this->components[$my_field]->fields_related_model as $fields_related)
				{
				
					$arr_select[]=$model_name_related.'.`'.$fields_related.'` as `'.$model_name_related.'_'.$fields_related.'`';
				
				}
				
				$arr_model[]=$model_name_related;
				
				//Set the where connection
				
				$arr_where[]=$this->name.'.`'.$my_field.'`='.$model_name_related.'.`'.Webmodel::$model[$model_name_related]->idmodel.'`';
			
			}
			
			//Now define inverse relationship...
			
			foreach($this->related_models as $model_name_related => $fields_related)
			{
			
				foreach($fields_related as $field_related)
				{
				
					$arr_select[]=$model_name_related.'.`'.$field_related.'` as `'.$model_name_related.'_'.$field_related.'`';
					
				}
				
				$arr_model[]=$model_name_related;
				
				$arr_where[]=$this->name.'.`'.$this->idmodel.'`='.$model_name_related.'.`'.$fields_related[0].'`';
			
			}
		
		}

		//Final fields from use in query
		
		$fields=implode(", ", $arr_select);

		//The tables used in the query
		
		$arr_model=array_unique($arr_model, SORT_STRING);

		$selected_models=implode(", ", $arr_model);
		
		//Conditions for the select query for related fields in the model
		$where=implode(" and ", $arr_where);

		//$conditions is a variable where store the result from $arr_select and $arr_extra_select
		
		if(preg_match('/^where/', $conditions) || preg_match('/^WHERE/', $conditions))
		{
			
			$conditions=str_replace('where', '', $conditions);
			$conditions=str_replace('WHERE', '', $conditions);

			$conditions='WHERE '.$where.' and '.$conditions;

		}
		else
		{
			
			$conditions='WHERE '.$where.' '.$conditions;

		}

		//$this->create_extra_fields();
		
		//Make the query...
		
		$arr_distinct[$this->distinct]='';
		$arr_distinct[0]='';
		$arr_distinct[1]=' DISTINCT ';
		
		$query=MySQLClass::webtsys_query('select '.$arr_distinct[$this->distinct].' '.$fields.' from '.$selected_models.' '.$conditions, $this->db_selected);
		
		$this->distinct=0;
		
		return $query;
		
	}

	//This method count num rows for the sql condition
	
	/**
	* This method is used for count the number of rows from a conditions.
	*
	* Using this method you count number of rows affected by $conditions. $conditions use the same sql sintax that $this->select 
	*
	* @param string $conditions is a string containing a sql string beginning by "where". Example: where id=1.
	* @param string $field The field to count, if no is set $field=$this->idmodel.
	* @param string $fields_for_count Array for fields used for simple counts based on foreignkeyfields.
	*/

	public function select_count($conditions, $field='', $fields_for_count=array())
	{
	
		$this->set_phango_connection();
		
		if($field=='')
		{
		
			$field=$this->idmodel;
		
		}
	
		$arr_model=array($this->name);
		$arr_where=array('1=1');
		
		$arr_check_count=array();
		
		foreach($fields_for_count as $key_component)
		{
		
			if(isset($this->components[$key_component]))
			{
		
				$component=$this->components[$key_component];
			
				if(get_class($component)=='ForeignKeyField')
				{
				
					$table_name=$component->related_model;
				
					if(isset($arr_check_count[$table_name]))
					{
				
						$table_name.='_'.uniqid();
						
					}
				
					$arr_model[]=$component->related_model.' as '.$table_name;
			
					$arr_where[]=$this->name.'.`'.$key_component.'`='.$table_name.'.`'.Webmodel::$model[$component->related_model]->idmodel.'`';
					
					$arr_check_count[$table_name]=1;
				
				}
				
			}
		}
		
		foreach($this->related_models as $model_name_related => $fields_related)
		{
			
			$arr_model[]=$model_name_related;
			
			$arr_where[]=$this->name.'.`'.$this->idmodel.'`='.$model_name_related.'.`'.$fields_related[0].'`';
		
		}
		
		$where=implode(" and ", $arr_where);
		
		if(preg_match('/^where/', $conditions) || preg_match('/^WHERE/', $conditions))
		{
			
			$conditions=str_replace('where', '', $conditions);
			$conditions=str_replace('WHERE', '', $conditions);

			$conditions='WHERE '.$where.' and '.$conditions;
			
		}
		else
		{
			
			$conditions='WHERE '.$where.' '.$conditions;

		}
		
		$query=MySQLClass::webtsys_query('select count('.$this->name.'.`'.$field.'`) from '.implode(', ', $arr_model).' '.$conditions, $this->db_selected);
		
		list($count_field)= MySQLClass::webtsys_fetch_row($query);

		return $count_field;

	}

	/**
	* This method delete rows for the sql condition
	*
	* This method is used for delete rows based in a sql conditions. If you use $this->register method for create new fields for model, $this->delete will delete all rows from model with foreignkeys related with this model. This thing is necessary because foreignkeys need to be deleted if you deleted its related model.
	*
	* @param string $conditions Conditions have same sintax that $conditions from $this->select method
	*/

	public function delete($conditions="")
	{
	
		$this->set_phango_connection();
	
		foreach($this->components as $name_field => $component)
		{
		
			if(method_exists($component,  'process_delete_field'))
			{
			
				$component->process_delete_field($this, $name_field, $conditions);
			
			}
		
		}
		
		//Delete rows on models with foreignkeyfields to this model...
		//You need load all models with relationship if you want delete related rows...
		
		if(count($this->related_models_delete)>0)
		{
			
			$arr_deleted=$this->select_to_array($conditions, array($this->idmodel), 1);
			
			$arr_id=array_keys($arr_deleted);
			
			$arr_id[]=0;
			
			foreach($this->related_models_delete as $arr_set_model)
			{
				
				if( isset( Webmodel::$model[ $arr_set_model['model'] ]->components[ $arr_set_model['related_field'] ] ) )
				{
					
					Webmodel::$model[ $arr_set_model['model'] ]->delete('where '.$arr_set_model['related_field'].' IN ('.implode(', ', $arr_id).')');
				
				}
			
			}
			
		}

 		return MySQLClass::webtsys_query('delete from '.$this->name.' '.$conditions, $this->db_selected);
		
	}
	
	/**
	* A helper function for obtain an array from a result of $this->select
	*
	* @param mixed $query The result of an $this->select operation
	*/
	
	public function fetch_row($query)
	{
	
		$this->set_phango_connection();
	
		return MySQLClass::webtsys_fetch_row($query);
	
	}
	
	/**
	* A helper function for obtain an associative array from a result of $this->select
	*
	* @param mixed $query The result of an $this->select operation
	*/
	
	public function fetch_array($query)
	{
	
		$this->set_phango_connection();
	
		return MySQLClass::webtsys_fetch_array($query);
	
	}
	
	/**
	* A helper function for obtain the last insert id.
	*
	* @param mixed $query The last insert id.
	*/
	
	public function insert_id()
	{
		
		$this->set_phango_connection();
		
		return MySQLClass::webtsys_insert_id();
	
	}

	/**
	* A helper function for get fields names of the model from the array $components
	*
	* This method is used if you need the fields names from a model for many tasks, for example, filter fields.
	*/

	public function all_fields()
	{
	
		if(count($this->forms)==0)
		{
		
			$this->create_form();
		
		}
	
		return array_keys($this->forms);

	}
	
	/**
	* A helper function for get fields names of the model from the array $components except some fields.
	*
	* This method is used if you need the fields names from a model for many tasks, for example, filter fields and you don't want all fields.
	*
	* @param array $arr_strip Array with the fields that you don't want on returned array.
	*/
	
	public function stripped_all_fields($arr_strip)
	{
	
		$arr_total_fields=$this->all_fields();

		return array_diff($arr_total_fields, $arr_strip);

	}
	
	/**
	* Internal method for check value for a field.
	*
	* @param string $key Defines the field used for insert the value
	* @param mixed $value The value to check
	*/
	
	public function check_element($key, $value)
	{
	
		return $this->components[$key]->check($value);
	
	}
	
	/**
	* A dummy function for internal tasks on $this->check_all method
	*
	* @param string $key Defines the field used for insert the value
	* @param mixed $value The value to check
	*/
	
	public function no_check_element($key, $value)
	{
	
		return $value;
	
	}

	/**
	* Check if components are valid, if not fill $this->std_error
	*
	* Check if an array of values for fill a row from a model are valid before insert on database. 
	*
	* @param array $post Is an array with data to update. You have a key that represent the name of field to fill with data, and the value that is the data for fill.
	*/

	public function check_all($post)
	{
		
		load_lang('error_model');
	
		//array where sanitized values are stored...
		
		$func_check='check_element';
		
		if($this->prev_check==1)
		{
		
			$func_check='no_check_element';
		
		}

		$arr_components=array();

		$set_error=0;

		$arr_std_error=array();

		//Make a foreach inside components, fields that are not found in components, are ignored
		
		foreach($this->components as $key => $value)
		{
			
			//If is set the variable for this component make checking

			if(isset($post[$key]))
			{

				//Check if the value is valid..

				$arr_components[$key]=$this->$func_check($key, $post[$key]);

				//If value isn't valid and is required set error for this component...

				if($this->components[$key]->required==1 && $arr_components[$key]=="")
				{	

					//Set errors...

					if($this->components[$key]->std_error=='')
					{

						$this->components[$key]->std_error=Webmodel::$l_['common']->lang('field_required', 'Field required');

					}

					$arr_std_error[]=Webmodel::$l_['error_model']->lang('check_error_field', 'Error in field').' '.$key.' -> '.$this->components[$key]->std_error. ' ';
					$set_error++;
	
				}
		
			}
			else if($this->components[$key]->required==1)
			{
	
				//If isn't set the value and this value is required set std_error.

				$arr_std_error[]=Webmodel::$l_['error_model']->lang('check_error_field_required', 'Error: Field required').' '.$key.' ';
	
				if($this->components[$key]->std_error=='')
				{

					$this->components[$key]->std_error=Webmodel::$l_['common']->lang('field_required', 'Field required');

				}
	
				$set_error++;

			}

		}

		//Set std_error for the model where is stored all errors in checking...

		$this->std_error=implode(', ', $arr_std_error);

		//If error return false

		if($set_error>0)
		{

			return 0;

		}

		//If not return values sanitized...

		return $arr_components;

	}

	/**
	* Simple method for secure if you don't want that a user send values to a fields of a model.
	*
	* This method is used if you don't want that the users via POST or GET send values to a field. This method simply delete the fields from the model. With field destroyed is impossible write in it.
	*
	* @param array $arr_components Array with fields names that you want delete from model.
	*/

	public function unregisters($arr_components=array())
	{

		foreach($arr_components as $value)
		{
			$stack[$value]=$this->components[$value];
			unset($this->components[$value]);
		}

		return $stack;

	}
	
	public function unset_no_required($post)
	{
	
		return filter_fields_array($this->arr_fields_updated, $post);
	
	}

	/**
	* Method for create an array of forms used for create html forms.
	*
	* This method is used for initialize an ModelForm array. This array is used for create a form based on fields of the model.
	*
	* @param array $fields_form The values of this array are used for obtain ModelForms from the fields with the same key that array values.
	*/
	
	public function create_form($fields_form=array())
	{

		//With function for create form, we use an array for specific order, after i can insert more fields in the form.

		$this->forms=array();
		
		$arr_form=array();
		
		if(count($fields_form)==0)
		{
		
			$fields_form=array_keys($this->components);
			
		}
		
		//foreach($this->components as $component_name => $component)
		foreach($fields_form as $component_name)
		{
		
			if(isset($this->components[$component_name]))
			{
			
				$component=&$this->components[$component_name];
			
				//Create form from model's components

				$this->forms[$component_name]=new ModelForm($this->name, $component_name, $component->form, Webmodel::set_name_default($component_name), $component, $component->required, '');
				
				$this->forms[$component_name]->set_all_parameters_form($component->get_parameters_default());
				
				if($this->components[$component_name]->label=='')
				{
				
					$this->components[$component_name]->label=ucfirst($component_name);
				
				}
				
				$this->forms[$component_name]->label=$this->components[$component_name]->label;

				//Set parameters to default
				//$parameters_value=$this->components[$component_name]->parameters;

				/*if($this->forms[$component_name]->parameters[2]==0)
				{*/
	
				//$this->forms[$component_name]->parameters=$this->components[$component_name]->parameters;
					

				//}

				//Use method from ModelForm for set initial parameters...

				//$this->forms[$component_name]->set_parameter_value($parameters_initial_value);
				
			}

		}

	}

	/**
	* Method for obtain an array with all errors in components
	* 
	* This method is used for obtain errors when a transaction (insert, update) was failed.
	*
	*/
	
	public function return_error_form()
	{

		$arr_error=array();

		foreach($this->components as $component_name => $component)
		{

			$arr_error[$component_name]=$component->std_error;

		}

		return $arr_error;

	}
	
	/**
	* Method for reset required fields.
	*
	* Method for reset required fields from components. Use this if you need update a field from a model but you don't want update other required fields.
	*/
	
	public function reset_require()
	{

		foreach($this->components as $component_name => $component)
		{

			$this->save_required[$component_name]=$this->components[$component_name]->required;
		
			$this->components[$component_name]->required=0;

		}

	}
	
	/**
	* Method for load saved required values for the fields...
	*
	* Method for load required values fields from components. Use this if you need recovery required values if you reseted them...
	*
	*/
	
	public function reload_require()
	{
		
		foreach($this->save_required as $field_required => $value_required)
		{
		
			$this->components[$field_required]->required=$this->save_required[$field_required];

		}

	}
	
	/**
	* Method used by form views for know if the form from this model have FileField...
	*
	* Internal method used for set enctype variable, necessary for diverses views for forms.
	*/

	public function set_enctype_binary()
	{

		$this->enctype='enctype="multipart/form-data"';

	}
	
	/**
	* API definition for method extensions based in function __call
	*
	* This method is used for define an easy format for create extensions for Webmodel class.
	*
	* For create una extension, you need create a file called name_extension.php on libraries/classes_extensions/ directory where name_extension is the basic name of new method.
	* On name_extension.php you must create a function with this name and arguments:
	* 
	* Example: function name_extension_method_class($class, argument1, $argument2, ...)
	*
	*
	* @param string $name_method Name of the new method
	* @param array $arguments An array with the arguments used in the new method
	*/
	
	public function __call($name_method, $arguments)
	{
	
		//load_libraries(array('classes_extensions/'.$name_method));
	
		array_unshift($arguments, $this);
	
		return call_user_func_array($name_method.'_method_class', $arguments);
	
	}
	
	/**
	* Experimental method for check elements on a where string
	*
	* @param array $arr_where An array with values to check
	*/
	
	static public function check_where($arr_where)
	{
	
		foreach($arr_where as $key => $value)
		{
		
			$arr_where[$key]=$this->components[$key]->check($value);
		
		}
		
		return $arr_where;
	
	}
	
	/**
	* A method for add components or fields (fields of a table on a db) to a model(table of a db).
	*
	* This is a method for create new fields for a model. You can create a field on a table with two methods: first, directly using fields or components classes, second, with this method. This method is recommended because give to you more info about your model to your component.
	*
	* @param string $name The name of the model 
	* @param string $type Field type, based on a phangofield class
	* @param string $arguments Array with arguments for construct the new field
	* @param boolean $required A boolean used for set the default required value
	*/
	public function register($name, $type, $arguments, $required=0)
	{
	
		$rc=new \ReflectionClass($type);
		$this->components[$name]=$rc->newInstanceArgs($arguments);
		//Set first label...
		$this->components[$name]->label=Webmodel::set_name_default($name);
		$this->components[$name]->name_model=$this->name;
		$this->components[$name]->name_component=$name;
		$this->components[$name]->required=$required;
		
		$this->components[$name]->set_relationships();
	
	}
	
	/**
	*
	* A experimental method for insert a form inside of $this->forms array after of a chosen field.
	*
	* This method us used for insert a form field inside of $this->forms array after of a chosen field.
	*
	* @param string $name_form_after Name of the form inside on $this->forms where you want put the new form after
	*
	* @param string $name_form_new Name of the new form after of $name_form_after
	*
	* @param string $form_new The new form, created using ModelForm class.
	*
	*/
	
	public function insert_after_field_form($name_form_after, $name_form_new, $form_new)
	{
	
		$arr_form_new=array();
	
		foreach($this->forms as $form_key => $form_field)
		{
		
			$arr_form_new[$form_key]=$form_field;
			
			if($form_key==$name_form_after)
			{
				
				$arr_form_new[$name_form_new]=$form_new;
			
			}
		
		}
		
		$this->forms=$arr_form_new;
	
	}
	
	
	/**
	* This method is used for checking if you prefer strings for where_sql.
	*/
	
	public function check_where_sql($name_component, $value)
	{
	
		return $this->components[$name_component]->check($value);
	
	}
	
	/**
	* A internal helper function 
	*
	* @param string $name Name for process
	*
	*/

	static public function set_name_default($name)
	{

		return ucfirst(str_replace('_', ' ', $name));

	}

}
?>