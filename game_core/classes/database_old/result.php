<?php defined('CORE_PATH') or die('No direct script access.');
/**
 * @file    Result.php
 * @author  Daniel Becker   <becker_leinad@hotmail.com>
 * @date    30.03.2015
 * @package game_core
 * @subpackage Database
 *
 * @description
 * This class hold the newest result-set and rows of the last statement
 */

class Database_Result {

	/**
	 * @var null|PDOStatement       the result-set object
	 */
	private $o_result_set = null;

	/**
	 * @var null|array              a complete array of all effected tows of the last statement
	 */
	private $a_result_rows = null;

	/**
	 * @var null|Database_Result    hold the actual instance of the class.
	 */
	private static $instance = null;

	/**
	 * Get singleton instance of the class or create new one, if no exists.
	 *
	 * @param  PDOStatement     $o_set_result   the requested result-set of the last SQL-Statement
	 * @return Database_Result                  the instance of the class
	 */
	public static function getInstance($o_set_result) {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		//set the result-set object and fetch all rows
		self::$instance->set_result_set($o_set_result);
		self::$instance->set_result_rows($o_set_result);

		//return the instance
		return self::$instance;
	}

	/**
	 * Defined the constructor as private, because of single pattern.
	 */
	private function __construct ()
	{ }

	/**
	 * Defined the clone function as private, because of single pattern.
	 */
	private function __clone()
	{ }

	/**
	 * A method to set the result-set in this class
	 *
	 * @param PDOStatement      $o_set_result    the requested result-set of the last SQL-Statement
	 */
	private function set_result_set($o_set_result)
	{
		//Check if it is not null AND its a object of PDOStatement
		if(!is_null($o_set_result) && is_object($o_set_result) && is_a($o_set_result,'PDOStatement'))
			$this->o_result_set = $o_set_result;
	}

	/**
	 * A method to fetch all rows and set it to the internal member
	 *
	 * @param PDOStatement      $o_set_result   the requested result-set of the last SQL-Statement
	 */
	private function set_result_rows($o_set_result)
	{
		//Check if it is not null AND its a object of PDOStatement
		if(!is_null($o_set_result) && is_object($o_set_result) && is_a($o_set_result,'PDOStatement'))
			$this->a_result_rows = $o_set_result->fetchAll(PDO::FETCH_ASSOC);;
	}

	/**
	 * This method checks, if any rows was affected by the last statement
	 *
	 * @return bool     TRUE if any row was affected, FALSE otherwise.
	 */
	public function success()
	{
		if($this->o_result_set->rowCount() > 0 || $this->o_result_set->fetchColumn() > 0)
			return true;
		else
			return false;
	}

	/**
	 * Return all affected/fetched rows from the last statement (mostly used with SELECT-Statements)
	 *
	 * @return array|null   all affected rows
	 */
	public function get_entries()
	{ return $this->a_result_rows; }

	/**
	 * Return how many rows are in the result-set
	 *
	 * @return int      the number of rows
	 */
	public function count_entries()
	{ return sizeof($this->a_result_rows); }

	/**
	 * This method will return an single entry of the result-array.
	 * If the param is higher then the size of the array it will be return null
	 *
	 * @param int   $i_entry_number     the index of an row in the result-array
	 *
	 * @return null|array               an array with an single row entry, or null if it is not exist
	 */
	public function get_single_entry($i_entry_number)
	{
		if($i_entry_number <= ($this->count_entries()-1))
			return $this->a_result_rows[$i_entry_number];
		else
			return null;
	}
}