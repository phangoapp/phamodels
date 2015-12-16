<?php

namespace PhangoApp\PhaModels;

if(!function_exists('mysqli_query'))
{

	throw new \Exception('Error: Mysql database don\'t supported by php');

}

class SQLClass {

	static public $debug=true;
	
	//static public $persistent='p:';
	static public $persistent='';

	static public function print_sql_fail($sql_fail, $server_data='default')
	{

		$error=mysqli_error(Webmodel::$connection[$server_data]);

		if($error!='' && SQLClass::$debug==true)
		{
			//echo '<p>Error: '.$sql_fail.' -> '.$error.'</p>';
			throw new \Exception('Error: '.$sql_fail.' -> '.$error);
		}

	}


	static public function webtsys_query( $sql_string , $server_data='default')
	{

		
		$query = mysqli_query(Webmodel::$connection[$server_data], $sql_string );
		
		SQLClass::print_sql_fail($sql_string, $server_data);

		Webmodel::$save_query++;
		
		return $query;
	} 

	static public function webtsys_affected_rows( $idconnection , $server_data='default')
	{

		$num_rows = mysqli_affected_rows(Webmodel::$connection[$server_data], $idconnection );

		return $num_rows;
	} 

	static public function webtsys_close( $idconnection )
	{

		mysqli_close( $idconnection );

		return 1;
	} 

	static public function webtsys_fetch_array( $query ,$assoc_type=0)
	{
		
		$arr_assoc[0]=MYSQL_ASSOC;
		$arr_assoc[1]=MYSQL_NUM;
		
		$arr_final = mysqli_fetch_array( $query ,$arr_assoc[$assoc_type]);

		return $arr_final;
	} 

	static public function webtsys_fetch_row( $query )
	{	
		$arr_final = mysqli_fetch_row( $query );

		return $arr_final;
	} 

	static public function webtsys_get_client_info($server_data='default')
	{

		$version = mysqli_get_client_info(Webmodel::$connection[$server_data]);

		return $version;
	} 

	static public function webtsys_get_server_info($server_data='default')
	{

		$version = mysqli_get_server_info(Webmodel::$connection[$server_data]);

		return $version;
	} 

	static public function webtsys_insert_id($server_data='default')
	{

		$idinsert = mysqli_insert_id(Webmodel::$connection[$server_data]);

		return $idinsert;
	} 

	static public function webtsys_num_rows( $query )
	{
	$num_rows = mysqli_num_rows( $query );

	return $num_rows;
	} 

	/*static public function connection_database( $host_db, $login_db, $contra_db, $db )
	{
	global $con_persistente;

	Webmodel::$connection = $con_persistente( $host_db, $login_db, $contra_db );

	webtsys_select_db( $db );

	return Webmodel::$connection;
	}*/

	static public function webtsys_connect( $host_db, $login_db, $contra_db , $server_data='default')
	{

		Webmodel::$connection[$server_data]=mysqli_init();
		
		if ( !( mysqli_real_connect(Webmodel::$connection[$server_data], SqlClass::$persistent.$host_db, $login_db, $contra_db ) ) )
		{
			
			return false;
			
		} 
		
		return true;
		
		//return Webmodel::$connection;
	} 

	static public function webtsys_select_db( $db , $server_data='default')
	{

		$result_db=mysqli_select_db(Webmodel::$connection[$server_data], $db);
		
		if($result_db==false)
		{

			return 0;

		}
		
		return 1;
	} 

	static public function webtsys_escape_string($sql_string, $server_data='default')
	{

		return mysqli_real_escape_string(Webmodel::$connection[$server_data], $sql_string);

	}

	static public function webtsys_error($server_data='default')
	{

		return mysqli_error(Webmodel::$connection[$server_data]);

	}

}

?>
