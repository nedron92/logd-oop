<?php defined('CORE_PATH') or die('No direct script access.');
/**
 * @file    Connection.php
 * @author  Daniel Becker <becker_leinad@hotmail.com>
 * @date    26.03.2015
 * @package game_core
 * @subpackage Database
 *
 * @description
 * This class implements a database connection via the PHP::PDO
 *
 */

class Database_Connection {

	/**
	 * @var null|PDO    the database connection object
	 */
	private $o_database_connection = null;

	/**
	 * @var null|Database_Connection  hold the actual instance of the class.
	 */
	private static $instance = null;


	/**
	 * Get singleton instance of the class or create new one, if no exists.
	 *
	 * @return Database_Connection   the instance
	 */
	public static function getInstance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor of the class.
	 * set a new database connection with the specified connection strings
	 * at ".dbconfig.php" (ROOT-DIRECTORY).

	 */
	private function __construct ()
	{
		include dirname(LOGD_ROOT).'/.dbconfig.php';

		try{
			$this->o_database_connection = new PDO(DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME.'', DB_USER, DB_PASSWD, array(
				PDO::ATTR_PERSISTENT => true
			));
		}catch (PDOException $e)
		{
			try{
				$message = $e->getMessage().'<br>There ist something wrong in your <b>.dbconfig.php</b>-File!, Check it.';
				$code = 'SQL-ERROR -> '.$e->getCode();
				throw new LOGD_Exception($message,$code);
			}catch (LOGD_Exception $exc)
			{ $exc->print_error(); }
		}

	}

	/**
	 * Defined the clone function as private, because of singleton pattern
	 */
	private function __clone()
	{}


	/**
	 * For parsing the where_clause array to a string concat with AND/OR or nothing.
	 *
	 * @param array     $a_where_clause     the array for the where-clause
	 *
	 * @return null|string                  a string, that contains the params for the where-clause
	 */
	private function parse_where_clause($a_where_clause)
	{
		$s_where_clause = null;
		if(is_array($a_where_clause))
		{
			$counter = 0;
			foreach($a_where_clause as $name => $value)
			{
				if(!strpos($value,'AND') && !strpos($value,'OR')
				   && !($counter >= sizeof($a_where_clause)-1))
					$s_where_clause.= $name." = '".$value."' AND ";
				else
				{
					if($counter >= sizeof($a_where_clause)-1) {
						$s_where_clause.= $name." = '".$value."'";
						break;
					}
					if(strpos($value,'AND'))  {
						$value = str_replace('AND',null,$value);
						$s_where_clause.= $name." = '".$value."' AND ";
					}elseif(strpos($value,'OR')) {
						$value = str_replace('OR',null,$value);
						$s_where_clause.= $name." = '".$value."' OR ";
					}
				}
				$counter++;
			}
		}

		return $s_where_clause;
	}

	/**
	 * This method will parsing/preparing the columns-mapped-array for the statement (insert)
	 *
	 * @param array         $a_column_mapping       the array with the mapped columns and values
	 *
	 * @return array|null                           an mapped array, which was prepared for the statement
	 *                                              (null on failure)
	 */
	private function parse_mapped_columns_values_insert($a_column_mapping)
	{
		if (sizeof($a_column_mapping) > 0) {
			$column_names = "";
			$column_values = "";

			foreach ( $a_column_mapping as $key => $value ) {
				$column_names .= $key . ",";
				if ( $value !== null ) {
					if ( strpos( $value, '(' ) && strpos( $value, ')' ) ) {
						$column_values .= $value . ',';
					} else {
						$column_values .= $this->o_database_connection->quote( $value ) . ',';
					}
				} else {
					$column_values .= "NULL,";
				}
			}

			if ($column_names[strlen($column_names) - 1] === ',')
				$column_names[strlen($column_names) - 1] = substr_replace($column_names[strlen($column_names) - 1], NULL, -1, 1);

			if ($column_values[strlen($column_values) - 1] === ',')
				$column_values[strlen($column_values) - 1] = substr_replace($column_values[strlen($column_values) - 1], "", -1, 1);

			$column_names = trim($column_names);
			$column_values = trim($column_values);

			$a_parsed_column_mapping = array($column_names,$column_values);

			return $a_parsed_column_mapping;
		}else{
			return null;
		}
	}

	/**
	 * This method will parsing/preparing the columns-mapped-array for the statement (insert)
	 *
	 * @param array         $a_column_mapping       the array with the mapped columns and values
	 *
	 * @return array|null                           an mapped array, which was prepared for the statement
	 *                                              (null on failure)
	 */
	private function parse_mapped_columns_values_update($a_column_mapping)
	{
		if (sizeof($a_column_mapping) > 0) {

			$s_mapped_columns = null;

			foreach ( $a_column_mapping as $key => $value ) {
				$s_mapped_columns .= $key . " = ";
				if ( $value !== null ) {
					if ( strpos( $value, '(' ) && strpos( $value, ')' ) ) {
						$s_mapped_columns .= $value . ',';
					} else {
						$s_mapped_columns .= $this->o_database_connection->quote( $value ) . ',';
					}
				} else {
					$s_mapped_columns .= "NULL,";
				}
			}

			if ($s_mapped_columns[strlen($s_mapped_columns) - 1] === ',')
				$s_mapped_columns[strlen($s_mapped_columns) - 1] = substr_replace($s_mapped_columns[strlen($s_mapped_columns) - 1], NULL, -1, 1);

			$s_mapped_columns = trim($s_mapped_columns);

			return $s_mapped_columns;
		}else{
			return null;
		}
	}
	/**
	 * This method select attributes from the given table,
	 * if the given column has a specified value (WHERE-Clause).
	 * You can specify the columns, which are selected, in an array.
	 * Also here it is possible to around the column-names
	 * with an 'count' or something like that.
	 * You can define options in an array (such as GROUP BY/ORDER BY..).
	 * The value can also be sorted with ASC/DESC.
	 *
	 * @param array             $a_columns          all needed columns in an array
	 * @param array|string      $m_table_names      the name of the tables to select from
	 *                                              (you can define an ALIAS here)
	 * @param array             $a_where_clause     an array with the where clause params
	 *                                              (column_name => column_value)
	 * @param array             $a_options          additional options for the statement
	 *
	 * @return  PDOStatement                        the result set of the statement
	 * @throws  LOGD_Exception                      it will throw a new Exception on Failure
	 *
	 */
	public function select_where($a_columns=array(),$m_table_names, $a_where_clause, $a_options = array())
	{
		$s_columns = null;
		if(sizeof($a_columns) > 0) $s_columns = implode(',',$a_columns);
		else $s_columns = '*';

		$s_tables = null;
		if(is_string($m_table_names)) $s_tables = DB_PREFIX.$m_table_names;
		elseif (is_array($m_table_names))
			foreach($m_table_names as $value)
				$s_tables.= DB_PREFIX.$value.',';

		$s_where_clause = $this->parse_where_clause($a_where_clause);

		$s_options = null;
		if(sizeof($a_options) > 0) $s_options = implode(' ',$a_options);

		$sql = "SELECT ".$s_columns." FROM ".$s_tables." WHERE ".$s_where_clause." ".$s_options;
		$o_pdo_prepare = $this->o_database_connection->prepare($sql);

		if($o_pdo_prepare->execute())
			return $o_pdo_prepare;
		else
			throw new LOGD_Exception('Failure with the statement<br>'.implode('<br>',$o_pdo_prepare->errorInfo()),'SQL-ERROR');

	}

	/**
	 * This method select attributes from the given table, WITHOUT WHERE.
	 * You can specify the columns, which are selected, in an array.
	 * Also here it is possible to around the column-names
	 * with an 'count' or something like that.
	 * You can define options in an array
	 * (such as GROUP BY/ORDER BY..).
	 * The value can also be sorted with ASC/DESC.
	 *
	 * @param array             $a_columns          all needed columns in an array
	 * @param string|array      $m_table_names      the name of the tables to select from
	 *                                              (you can define an ALIAS here)
	 * @param array             $a_options          additional options for the statement
	 *
	 * @return  PDOStatement                        the result set of the statement
	 * @throws  LOGD_Exception                      it will throw a new Exception on Failure
	 */
	public function select($a_columns=array(),$m_table_names, $a_options = array())
	{
		$s_columns = null;
		if(sizeof($a_columns) > 0) $s_columns = implode(',',$a_columns);
		else $s_columns = '*';

		$s_tables = null;
		if(is_string($m_table_names)) $s_tables = DB_PREFIX.$m_table_names;
		elseif (is_array($m_table_names))
			foreach($m_table_names as $value)
				$s_tables.= DB_PREFIX.$value.',';

		$s_options = null;
		if(sizeof($a_options) > 0)
		{
			foreach($a_options as $key => $value)
			{ $s_options.= $key.' '.$value.' '; }
		}

		$sql = "SELECT ".$s_columns." FROM ".$s_tables." ".$s_options;
		$o_pdo_prepare = $this->o_database_connection->prepare($sql);
		if($o_pdo_prepare->execute())
			return $o_pdo_prepare;
		else
			throw new LOGD_Exception('Failure with the statement<br>'.implode('<br>',$o_pdo_prepare->errorInfo()),'SQL-ERROR');
	}

	/**
	 * This method insert the specified values from the array (and mapped it
	 * back to column-name => column-value to the given table).
	 *
	 * @param string    $s_table_name       the table name for inserting
	 * @param array     $a_column_mapping   an array with column names and
	 *                                      there values (name => value)
	 *
	 * @return  PDOStatement                the result
	 * @throws  LOGD_Exception              it will throw a new Exception on Failure
	 */
	public function insert($s_table_name, $a_column_mapping=array())
	{
		$a_parsed_column_mapping = $this->parse_mapped_columns_values_insert($a_column_mapping);

		if(!is_null($a_parsed_column_mapping))
		{
			$s_column_names = $a_parsed_column_mapping[0];
			$s_column_values  = $a_parsed_column_mapping[1];
		}else
			$s_column_names = $s_column_values = null;

		$sql = "INSERT INTO ".DB_PREFIX.$s_table_name." (".$s_column_names.") VALUES (" . $s_column_values . ")";
		$o_pdo_prepare = $this->o_database_connection->prepare($sql);

		if($o_pdo_prepare->execute())
			return $o_pdo_prepare;
		else
			throw new LOGD_Exception('Failure with the statement<br>'.implode('<br>',$o_pdo_prepare->errorInfo()),'SQL-ERROR');

	}

	/**
	 * This method updates all columns in the given tables (with alias then) that matches
	 * the columns in the where-clause array.
	 *
	 * @param array|string      $m_table_names      the name of all updating tables in an array
                                                    (or simply one table as a string)
	 * @param array             $a_column_mapping   an array with column names and
	 *                                              there values (name => value)
	 * @param array             $a_where_clause     an array with the where clause params
	 *                                              (column_name => column_value)
	 *
	 * @return  PDOStatement                        the result
	 * @throws  LOGD_Exception                      it will throw a new Exception on Failure
	 */
	public function update($m_table_names,$a_column_mapping,$a_where_clause)
	{
		$s_tables = null;
		if(is_string($m_table_names)) $s_tables = DB_PREFIX.$m_table_names;
		elseif (is_array($m_table_names))
			foreach($m_table_names as $value)
				$s_tables.= DB_PREFIX.$value.',';

		if ($s_tables[strlen($s_tables) - 1] === ',')
			$s_tables[strlen($s_tables) - 1] = substr_replace($s_tables[strlen($s_tables) - 1], " ", -1, 1);

		$s_parsed_column_mapping = $this->parse_mapped_columns_values_update($a_column_mapping);
		$s_where_clause = $this->parse_where_clause($a_where_clause);

		$sql = "UPDATE ".$s_tables." SET ".$s_parsed_column_mapping." WHERE ".$s_where_clause;
		$o_pdo_prepare = $this->o_database_connection->prepare($sql);
		if($o_pdo_prepare->execute())
			return $o_pdo_prepare;
		else
			throw new LOGD_Exception('Failure with the statement<br>'.implode('<br>',$o_pdo_prepare->errorInfo()),'SQL-ERROR');
	}

	/**
	 * This method will delete the rows in the given tables, that matches the WHERE-clause.
	 *
	 * @param array|string      $m_table_names      the name of all updating tables in an array
													(or simply one table as a string)
	 * @param array             $a_table_alias      the alias of the tables (leave empty for no alias)
	 * @param array             $a_where_clause     an array with the where clause params
	 *                                              (column_name => column_value)
	 *
	 * @return  PDOStatement                        the result
	 * @throws  LOGD_Exception                      it will throw a new Exception on Failure
	 */
	public function delete($m_table_names,$a_table_alias=array(),$a_where_clause)
	{
		$s_tables_alias = null;
		if(is_string($m_table_names)) $s_tables_alias = DB_PREFIX.$m_table_names;
		elseif (is_array($m_table_names))
			foreach($m_table_names as $key => $value) {
				$s_tables_alias.= DB_PREFIX.$value.' AS '.$a_table_alias[$key].',';
			}

		if ($s_tables_alias[strlen($s_tables_alias) - 1] === ',')
			$s_tables_alias[strlen($s_tables_alias) - 1] = substr_replace($s_tables_alias[strlen($s_tables_alias) - 1], " ", -1, 1);

		$s_where_clause = $this->parse_where_clause($a_where_clause);
		$sql = "DELETE ".implode(',',$a_table_alias)." FROM ".$s_tables_alias." WHERE ".$s_where_clause;
		$o_pdo_prepare = $this->o_database_connection->prepare($sql);

		var_dump($sql);
		$sql2 = 'DELETE :aliases FROM :tables WHERE :where_clause';
		$o_pdo_prepare2 = $this->o_database_connection->prepare($sql2);


		var_dump(		$o_pdo_prepare2->execute(
			array(
				':aliases' => 't1',
				':tables' => 'logd_test',
				':where_clause' => 't1.id = 1'
			)
		));

		if($o_pdo_prepare->execute())
			return $o_pdo_prepare;
		else
			throw new LOGD_Exception('Failure with the statement<br>'.implode('<br>',$o_pdo_prepare->errorInfo()),'SQL-ERROR');
	}
} 