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
	 * the constructor for this driver.
	 * it set the correct connection-string and throw and Exception if there is anything wrong with the config
	 *
	 * @return $this|static|self    the database-driver object
	 * @throws \LOGD_Exception
	 */
	public function __construct() {

		//try to build the connection-string for mysql with the the information from the dbconfig-file
		//and catch the thrown PDOException
		try{
			$this->o_database_connection = new \PDO(DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME.'', DB_USER, DB_PASSWD, array(
				\PDO::ATTR_PERSISTENT => true
			));
		}catch (\PDOException $e)
		{
			// collect the current error information and throw it further to the \LOGD_Exception,
			// then print the error-view
			try{
				$message = $e->getMessage() . __('error_dbconfig_file','errors');
				$code = 'SQL-ERROR -> '.$e->getCode();
				throw new \LOGD_Exception($message,$code);
			}catch (\LOGD_Exception $exc)
			{ $exc->print_error(); }
		}
	}


	/**
	 * This method is a wrapper for an insert-into sql-command
	 *
	 * @param $s_table_name
	 *
	 * @return $this
	 */
	public function insert_into($s_table_name) {
		parent::insert_into($s_table_name);
		var_dump(__FUNCTION__);
		var_dump($this->o_database_connection);

		return $this;
	}


}