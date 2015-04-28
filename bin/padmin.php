<?php

include(__DIR__.'/../../../../autoload.php');

use PhangoApp\PhaModels\Webmodel;
use PhangoApp\PhaModels\MySQLClass;

$options = getopt("m:");

if(!isset($options['m']))
{

	die("Use: php padmin.php -m app/model\n");

}

//Load the config.

include(__DIR__.'/../../../../config.php');

Webmodel::$model_path=__DIR__.'/../../../../';

$model=WebModel::load_model($options['m']);

try {

	$first_item=current($model);
	
	$first_item->connect_to_db();
	
} catch(Exception $e)
{

	echo $e->getMessage()."\n";
	die;

}

//print_r(get_declared_classes());



update_table($model);

/**
* This Function is used for padmin.php for create new tables and fields based in Webmodel class.
*
* @param Webmodel $model The model used for create or update a sql table.
*/

function update_table($model)
{
	//include(__DIR__.'/../src/Databases/'.Webmodel::$type_db.'.php');
	
	$arr_sql_index=array();
	$arr_sql_set_index=array();
	
	$arr_sql_unique=array();
	$arr_sql_set_unique=array();
	
	$arr_etable=array();
	
	$query=MySQLClass::webtsys_query("show tables");
		
	while(list($table)=MySQLClass::webtsys_fetch_row($query))
	{
	
		$arr_etable[$table]=1;
	
	}
	
	foreach($model as $key => $thing)
	
	{
		
		$arr_table=array();
		
		$allfields=array();
		$fields=array();
		$types=array();
	
		$field="";
		$type=""; 
		$null=""; 
		$key_db=""; 
		$default=""; 
		$extra="";
		$key_field_old=$model[$key]->idmodel;
		
		if(!isset($arr_etable[$key]))
		{
			//If table not exists make this
			
			foreach($model[$key]->components as $field => $data)
			{
			
				$arr_table[]='`'.$field.'` '.$model[$key]->components[$field]->get_type_sql();

				//Check if indexed
				
				if($model[$key]->components[$field]->indexed==true)
				{
				
					$arr_sql_index[$key][$field]='CREATE INDEX `index_'.$key.'_'.$field.'` ON '.$key.'(`'.$field.'`);';
					$arr_sql_set_index[$key][$field]='';
				
				}
				
				//Check if unique
				
				if($model[$key]->components[$field]->unique==true)
				{
				
					$arr_sql_unique[$key][$field]=' ALTER TABLE `'.$key.'` ADD UNIQUE (`'.$field.'`)';
					$arr_sql_set_unique[$key][$field]='';
				
				}
				
				//Check if foreignkeyfield...
				if(isset($model[$key]->components[$field]->related_model))
				{

					//Create indexes...
					
					$arr_sql_index[$key][$field]='CREATE INDEX `index_'.$key.'_'.$field.'` ON '.$key.'(`'.$field.'`);';
					
					$table_related=$model[$key]->components[$field]->related_model;
					
					$id_table_related=load_id_model_related($model[$key]->components[$field]);

					//'Id'.ucfirst($model[$key]->components[$field]->related_model);				
					
					$arr_sql_set_index[$key][$field]='ALTER TABLE `'.$key.'` ADD CONSTRAINT `'.$field.'_'.$key.'IDX` FOREIGN KEY ( `'.$field.'` ) REFERENCES `'.$table_related.'` (`'.$id_table_related.'`) ON DELETE RESTRICT ON UPDATE RESTRICT;';

				}
			}
			
			$sql_query="create table `$key` (\n".implode(",\n", $arr_table)."\n) DEFAULT CHARSET=utf8;\n";
			
			echo "Creating table $key\n";
			
			$query=MySQLClass::webtsys_query($sql_query);

			/*foreach($arr_sql_index as $key_data => $sql_index)
			{

				echo "---Creating index for ".$key_data."\n";

				$query=MySQLClass::webtsys_query($sql_index);
				$query=MySQLClass::webtsys_query($arr_sql_set_index[$key_data]);

			}*/

		}
		else
		if(isset($model[$key]))
		{
			//Obtain all fields of model
		
			foreach($model[$key]->components as $kfield => $value)
			{
		
				$allfields[$kfield]=1;
				
			}
		
			//unset($allfields['Id'.ucfirst($key)]);
			
			$arr_null['NO']='NOT NULL';
			$arr_null['YES']='NULL';

			unset($allfields[$model[$key]->idmodel]);
		
			$query=MySQLClass::webtsys_query("describe `".$key."`");
			
			list($key_field_old, $type, $null, $key_db, $default, $extra)=MySQLClass::webtsys_fetch_row($query);
		
			while(list($field, $type, $null, $key_db, $default, $extra)=MySQLClass::webtsys_fetch_row($query))
			{
		
				$fields[]=$field;
				$types[$field]=$type;
				$keys[$field]=$key_db;
				
				$null_set[$field]=$arr_null[$null];
				
			}
			
			foreach($fields as $field)
			{
		
				if(isset($allfields[$field]))
				{
		
					$type=strtoupper($types[$field]);
					
					unset($allfields[$field]);
					
					if($model[$key]->components[$field]->get_type_sql()!=($type.' '.$null_set[$field]))
					{
						
						$query=MySQLClass::webtsys_query('alter table `'.$key.'` modify `'.$field.'` '.$model[$key]->components[$field]->get_type_sql());
						
						echo "Upgrading ".$field." from ".$key."...\n";
						
				
					}
					
					//Check if indexed
				
					if($model[$key]->components[$field]->indexed==true && $keys[$field]=='')
					{
					
						$arr_sql_index[$key][$field]='CREATE INDEX `index_'.$key.'_'.$field.'` ON `'.$key.'`(`'.$field.'`);';
						$arr_sql_set_index[$key][$field]='';
					
					}
					
					//Check if unique
				
					if($model[$key]->components[$field]->unique==true && $keys[$field]=='')
					{
					
						$arr_sql_unique[$key][$field]=' ALTER TABLE `'.$key.'` ADD UNIQUE (`'.$field.'`)';
						$arr_sql_set_unique[$key][$field]='';
					
					}

					//Set index

					if(isset($model[$key]->components[$field]->related_model) && $keys[$field]=='')
					{

						
						$arr_sql_index[$key][$field]='CREATE INDEX `index_'.$key.'_'.$field.'` ON `'.$key.'`(`'.$field.'`);';
					
						$table_related=$model[$key]->components[$field]->related_model;
						
						$id_table_related=load_id_model_related($model[$key]->components[$field]);
						
						$arr_sql_set_index[$key][$field]='ALTER TABLE `'.$key.'` ADD CONSTRAINT `'.$field.'_'.$key.'IDX` FOREIGN KEY ( `'.$field.'` ) REFERENCES `'.$table_related.'` (`'.$id_table_related.'`) ON DELETE RESTRICT ON UPDATE RESTRICT;';
						

					}
					
					if(!isset($model[$key]->components[$field]->related_model) && $keys[$field]!='' && $model[$key]->components[$field]->indexed==false && $model[$key]->components[$field]->unique!=true)
					{
						
						echo "---Delete index for ".$field." from ".$key."\n";
						
						$query=MySQLClass::webtsys_query('DROP INDEX `index_'.$key.'_'.$field.'` ON '.$key);

					}
		
				}
		
				else
				
				{
		
					$allfields[$field]=0;
		
				}
		
			}
		
		}

		//Check if new id...

		if($key_field_old!=$model[$key]->idmodel)
		{

			$query=MySQLClass::webtsys_query('alter table `'.$key.'` change `'.$key_field_old.'` `'.$model[$key]->idmodel.'` INT NOT NULL AUTO_INCREMENT');

			echo "Renaming id for this model to ".$model[$key]->idmodel."...\n";

		}

		//Check if new fields...
	
		foreach($allfields as $new_field => $new)
		{
				
			if($allfields[$new_field]==1)
			{
		
				$query=MySQLClass::webtsys_query('alter table `'.$key.'` add `'.$new_field.'` '.$model[$key]->components[$new_field]->get_type_sql());

				echo "Adding ".$new_field." to ".$key."...\n";
				
				//Check if indexed
				
				if($model[$key]->components[$new_field]->indexed==true)
				{
				
					$arr_sql_index[$key][$new_field]='CREATE INDEX `index_'.$key.'_'.$new_field.'` ON `'.$key.'`(`'.$new_field.'`);';
					$arr_sql_set_index[$key][$new_field]='';
				
				}

				if(isset($model[$key]->components[$new_field]->related_model) )
				{

					/*echo "---Creating index for ".$new_field." from ".$key."\n";

					$query=MySQLClass::webtsys_query('CREATE INDEX index_'.$key.'_'.$new_field.' ON '.$key.'('.$new_field.')');*/
					
					$arr_sql_index[$key][$new_field]='CREATE INDEX `index_'.$key.'_'.$new_field.'` ON `'.$key.'`(`'.$new_field.'`);';
					
					$table_related=$model[$key]->components[$new_field]->related_model;
					
					$id_table_related=load_id_model_related($model[$key]->components[$new_field]);
					
					$arr_sql_set_index[$key][$new_field]='ALTER TABLE `'.$key.'` ADD CONSTRAINT `'.$new_field.'_'.$key.'IDX` FOREIGN KEY ( `'.$new_field.'` ) REFERENCES `'.$table_related.'` (`'.$id_table_related.'`) ON DELETE RESTRICT ON UPDATE RESTRICT;';

				}
		
			}
		
			else
		
			{
			
				/*if(isset($model[$key]->components[$new_field]->related_model) )
				{*/
				
					//Drop foreignkeyfield
					
				//Bug, need fixed.
				if($keys[$new_field]!='')
				{
				
					$query=MySQLClass::webtsys_query('ALTER TABLE `'.$key.'` DROP FOREIGN KEY '.$new_field.'_'.$key.'IDX');
					
				}
					
				
				//}

				$query=MySQLClass::webtsys_query('alter table `'.$key.'` drop `'.$new_field.'`');
			
				echo "Deleting ".$new_field." from ".$key."...\n";
		
			}
		
		}
		
		$arr_etable[$key]=0;
	
	}
	
	//Create Indexes...
	
	foreach($arr_sql_index as $model_name => $arr_index)
	{
		foreach($arr_sql_index[$model_name] as $key_data => $sql_index)
		{

			echo "---Creating index for ".$key_data." on model ".$model_name."\n";

			$query=MySQLClass::webtsys_query($sql_index);
			
			if($arr_sql_set_index[$model_name][$key_data]!='')
			{
				$query=MySQLClass::webtsys_query($arr_sql_set_index[$model_name][$key_data]);
			}

		}
	}
	
	//Create Uniques...
	
	foreach($arr_sql_unique as $model_name => $arr_index)
	{
		foreach($arr_sql_unique[$model_name] as $key_data => $sql_index)
		{

			echo "---Creating unique for ".$key_data." on model ".$model_name."\n";

			$query=MySQLClass::webtsys_query($sql_index);
			
			if($arr_sql_set_unique[$model_name][$key_data]!='')
			{
				$query=MySQLClass::webtsys_query($arr_sql_set_unique[$model_name][$key_data]);
			}

		}
	}
	
	/*foreach($arr_etable as $table => $value)
	{
	
		if($value==1)
		{
		
			$query=MySQLClass::webtsys_query('DROP TABLE `'.$table.'`');
			
			echo 'Deleting table '.$table."\n";
		
		}
	
	}*/

}

?>