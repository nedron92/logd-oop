<?php
/**
 * @file    mysql.php
 * @author  Daniel   <becker_leinad@hotmail.com>
 * @date    20.07.2015
*/

namespace Database;
defined('CORE_PATH') or die('No direct script access.');
/**
 * @package game_core
 * @subpackage database/driver
 *
 * @description
 * This class represents the driver for a mysql-database (also maria-db)
 */
class Driver_MySQL extends \Database_Drivers{

	/**
	 * @return $this|static|self
	 */
	public function __construct() {

		try{
			$this->o_database_connection = new \PDO(DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME.'', DB_USER, DB_PASSWD, array(
				\PDO::ATTR_PERSISTENT => true
			));
		}catch (\PDOException $e)
		{
			try{
				$message = $e->getMessage().'<br>There ist something wrong in your <b>.dbconfig.php</b>-File!, Check it.';
				$code = 'SQL-ERROR -> '.$e->getCode();
				throw new \LOGD_Exception($message,$code);
			}catch (\LOGD_Exception $exc)
			{ $exc->print_error(); }
		}
	}


	public function insert_into($s_table_name) {
		parent::insert_into($s_table_name);
		var_dump(__FUNCTION__);
		var_dump($this->o_database_connection);
	}


}