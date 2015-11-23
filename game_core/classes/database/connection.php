<?php
namespace database;
defined('CORE_PATH') or die('No direct script access.');

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

class Connection {

	/**
	 * @const string    The prefix (with correct namespace) of all Database-Drivers
	 */
	const DRIVER_PREFIX = 'Database\Driver_';

	/**
	 * @var Drivers    $o_instance     the current instance of the Database-Connection
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
	 * @return Drivers| \Database\Driver_MySQL     return the specific driver-object
	 * @throws \LOGD_Exception
	 */
	public static function factory()
	{
		//check if we have already an instance of a driver class
		if ( null === self::$o_instance ) {

			//try to load the specific driver class (check if the class exists),
			//based on the PREFIX with namespace and the database-type.
			//throw an Exception with the corresponding error, which driver class failed
			try {
				$s_class = self::DRIVER_PREFIX.DB_TYPE;

				if(!class_exists($s_class)) {
					$message = __('error_class_not_found','errors').'<strong>'.$s_class.'</strong><br>';
					$message.= __('error_wrong_driver','errors').'<strong>'.DB_TYPE.'</strong>';
					throw new \LOGD_Exception($message,900);
				}

				//set the current instance-object to the specific driver class and check the methods
				self::$o_instance =  new $s_class;
				self::$o_instance->check_methods();

			}catch (\LOGD_Exception $e) {
				$e->print_error();
			}
		}

		return self::$o_instance;
	}

}