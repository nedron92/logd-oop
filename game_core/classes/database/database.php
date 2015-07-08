<?php defined('CORE_PATH') or die('No direct script access.');
/**
 * @file    Database.php
 * @author  Daniel Becker  <becker_leinad@hotmail.com>
 * @date    30.03.2015
 * @package game_core
 * @subpackage Database
 *
 * @description
 * This class represents a wrapper for the connection and the result class and hold the Exceptions.
 *
 */

class Database_Database {

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
	 * @param array             $a_where_clause     a array with the where clause params
	 *                                              (column_name => column_value)
	 * @param array             $a_options          additional options for the statement
	 *
	 * @return  Database_Result                     the result set of the statement
	 *
	 */
	public static function select_where($a_columns=array(),$m_table_names, $a_where_clause, $a_options = array())
	{
		try{
			return Database_Result::getInstance(Database_Connection::getInstance()->select_where($a_columns,$m_table_names,$a_where_clause,$a_options));
		}catch (LOGD_Exception $exc)
		{ $exc->print_error(); return null; }
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
	 * @return  null|Database_Result        the result set of the statement
	 */
	public static function select($a_columns=array(),$m_table_names, $a_options = array())
	{
		try{
			return Database_Result::getInstance(Database_Connection::getInstance()->select($a_columns,$m_table_names,$a_options));
		}catch (LOGD_Exception $exc)
		{ $exc->print_error(); return null; }
	}

	/**
	 * This method insert the specified values from the array (and mapped it
	 * back to column-name => column-value to the given table).
	 *
	 * @param string    $s_table_name       the table name for inserting
	 * @param array     $a_column_mapping   an array with column names and
	 *                                      there values (name => value)
	 *
	 * @return  null|Database_Result        the result set of the statement
	 */
	public static function insert($s_table_name, $a_column_mapping=array())
	{
		try{
			return Database_Result::getInstance(Database_Connection::getInstance()->insert($s_table_name,$a_column_mapping));
		}catch (LOGD_Exception $exc)
		{ $exc->print_error(); return null; }
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
	 * @return  null|Database_Result                the result set of the statement
	 */
	public static function update($m_table_names,$a_column_mapping,$a_where_clause)
	{
		try{
			return Database_Result::getInstance(Database_Connection::getInstance()->update($m_table_names,$a_column_mapping,$a_where_clause));
		}catch (LOGD_Exception $exc)
		{ $exc->print_error(); return null; }
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
	 * @return  null|Database_Result        the result set of the statement
	 */
	public static function delete($m_table_names,$a_where_clause,$a_table_alias=array())
	{
		try{
			return Database_Result::getInstance(Database_Connection::getInstance()->delete($m_table_names,$a_where_clause,$a_table_alias));
		}catch (LOGD_Exception $exc)
		{ $exc->print_error(); return null; }
	}
} 