<?php
namespace database;
defined('CORE_PATH') or die('No direct script access.');

/**
 * @file    drivers.php
 * @author  Daniel   <becker_leinad@hotmail.com>
 * @date    20.07.2015
 * @package game_core
 * @subpackage database
 *
 * @description
 * This class represents the main class of all database-drivers.
 * It specified some main/equal methods of all drivers.
 */
abstract class Drivers implements iGeneral{

	/**
	 * @var array   $a_current_query     the current query fragments in an array
	 */
	protected $a_current_query = array();

	/**
	 * @var array   $a_column_values    the current columns with their values
	 */
	protected $a_column_values = array();

	/**
	 * @var null|\PDO  $o_database_connection  the database connection object
	 */
	protected $o_database_connection = null;

	/**
	 * The constructor of a database driver is abstracted, because of the need to implement it
	 * in all child-classes
	 *
	 * @abstract
	 */
	abstract protected function __construct();

	/**
	 * This method will return the current used driver with namespace.
	 *
	 * @return string   the driver name with namespace
	 */
	public function get_driver_name()
	{
		return get_class($this);
	}

	/**
	 * @param $s_table_name
	 */
	 public function insert_into($s_table_name)
	 {
		$this->a_current_query[__FUNCTION__] = $s_table_name;
	}

	/**
	 * @param $s_table_name
	 */
	public function select($s_table_name)
	{
		$this->a_current_query[__FUNCTION__] = $s_table_name;
	}

	/**
	 * @param $s_column
	 * @param $s_value
	 */
	public function add_column_values_by_string($s_column, $s_value)
	{
		$this->a_column_values[$s_column] = $s_value;
	}

	public function add_column_values_by_array($a_column_values = array())
	{
		foreach($a_column_values as $s_column => $s_value) {
			$this->a_column_values[$s_column] = $s_value;
		}
	}

	/**
	 * this method checks if the specific-driver-only methods have the specific prefix for readability.
	 * It will thrown an LOGD_Excepion if there is any failure.
	 *
	 * @throws \LOGD_Exception
	 */
	public function check_methods()
	{
		//get all methods of the this abstracted and the specific child class and define an failure-array
		$a_child_methods = get_class_methods($this->get_driver_name());
		$a_parent_methods = get_class_methods(__CLASS__);
		$a_failed_methods = array();

		//iterate over all child methods and check if they are in this parent class or not
		//if not, check if the prefix exist at the beginning.
		//If there is no prefix fill the failed-methods array with the information about it.
		foreach($a_child_methods as $s_child_method) {
			if(!in_array($s_child_method,$a_parent_methods)) {
				$s_method_prefix = substr($s_child_method,0,strlen(DB_TYPE));
				if($s_method_prefix === DB_TYPE) continue;
				$a_failed_methods[] = $this->get_driver_name().'::'.$s_child_method;
			}
		}

		//check if the failed-methods array have any entries, if yes - print it out and throw an exception
		if(sizeof($a_failed_methods) > 0)
		{
			$message = trim(__('error_check_methods','errors')).'<b>'.implode(', ',$a_failed_methods).'</b>';
			$message = preg_replace('/\t+/', '', $message);

			throw new \LOGD_Exception($message,1000);
		}
	}

	/**
	 * @return null|\PDO
	 */
	public function get_database_object()
	{
		return $this->o_database_connection;
	}

}