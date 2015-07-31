<?php defined('CORE_PATH') or die('No direct script access.');

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
abstract class Database_Drivers {

	/**
	 * @var array   $a_actual_query     the actual query fragments in an array
	 */
	protected $a_actual_query = array();

	/**
	 * @var array   $a_column_values    the actual columns with their values
	 */
	protected $a_column_values = array();

	/**
	 * @var null|\PDO  $o_database_connection  the database connection object
	 */
	protected $o_database_connection = null;

	/**
	 * The constructor of a database driver is abstracted, because of of the need to implement it
	 * in all child-classes
	 *
	 * @abstract
	 */
	abstract protected function __construct();

	/**
	 * This method will return the actual used driver with namespace.
	 *
	 * @return string   the driver name with namespace
	 */
	public function get_driver_name() {
		return get_class($this);
	}

	/**
	 * @param $s_table_name
	 */
	 public function insert_into($s_table_name) {
		$this->a_actual_query[__FUNCTION__] = $s_table_name;
	}

	/**
	 * @param $s_table_name
	 */
	public function select($s_table_name) {
		$this->a_actual_query[__FUNCTION__] = $s_table_name;
	}

	/**
	 * @param $s_column
	 * @param $s_value
	 */
	public function add_column_values_by_string($s_column, $s_value) {
		$this->a_column_values[$s_column] = $s_value;
	}

	public function add_column_values_by_array($a_column_values = array()) {
		foreach($a_column_values as $s_column => $s_value) {
			$this->a_column_values[$s_column] = $s_value;
		}
	}

	/**
	 * @throws LOGD_Exception
	 */
	public function check_methods() {
		$a_child_methods = get_class_methods($this->get_driver_name());
		$a_parent_methods = get_class_methods(__CLASS__);
		$a_failed_methods = array();


		foreach($a_child_methods as $s_child_method) {
			if(!in_array($s_child_method,$a_parent_methods)) {
				$s_method_prefix = substr($s_child_method,0,strlen(DB_TYPE));
				if($s_method_prefix === DB_TYPE) continue;
				$a_failed_methods[] = $this->get_driver_name().'::'.$s_child_method;
			}
		}

		if(sizeof($a_failed_methods) > 0)
		{
			$message = trim(__('error_check_methods','errors')).'<b>'.implode(', ',$a_failed_methods).'</b>';
			$message = preg_replace('/\t+/', '', $message);

			throw new LOGD_Exception($message,1000);
		}
	}

	/**
	 * @return null|PDO
	 */
	public function get_database_object() {
		return $this->o_database_connection;
	}

}