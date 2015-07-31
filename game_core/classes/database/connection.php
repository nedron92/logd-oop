<?php defined('CORE_PATH') or die('No direct script access.');

/**
 * @file    connection.php
 * @author  Daniel   <becker_leinad@hotmail.com>
 * @date    20.07.2015
 * @package game_core
 * @subpackage database
 *
 * @description
 * The database connection class.
 * This class will load the right driver, based on the DB_TYPE in '.dbconfig.php'
 */

class Database_Connection {

	/**
	 * @const string    The prefix (with correct namespace) of all Database-Drivers
	 */
	const DRIVER_PREFIX = '\Database\Driver_';

	/**
	 * @var Database_Drivers $o_instance    the current instance of the Database-Connection
	 */
	private static $o_instance = null;

	/**
	 * Defined the constructor as private, because of singleton pattern
	 */
	private function __construct()
	{ }

	/**
	 * Defined the clone function as private, because of singleton pattern
	 */
	private function __clone()
	{}


	/**
	 * The factory method will load the correct driver for the database-connection,
	 * based on the DB_TYPE constant in the '.dbconfig.php' file.
	 * It also will check, if driver-only-methods have the DB_TYPE prefix, because of
	 * readability and usage. (driver-only-methods are methods of a driver, that not specified in the
	 * abstract Database_Driver class)
	 *
	 * @return Database_Drivers| \Database\Driver_MySQL
	 * @throws LOGD_Exception
	 */
	public static function factory() {

		if ( null === self::$o_instance ) {

			try {
				$s_class = self::DRIVER_PREFIX.DB_TYPE;

				if(!class_exists($s_class)) {
					$message = __('error_class_not_found','errors').'<strong>'.$s_class.'</strong><br>';
					$message.= __('error_wrong_driver','errors').'<strong>'.DB_TYPE.'</strong>';
					throw new LOGD_Exception($message,900);
				}

				self::$o_instance =  new $s_class;
				self::$o_instance->check_methods();

			}catch (LOGD_Exception $e) {
				$e->print_error();
			}
		}

		return self::$o_instance;
	}

}