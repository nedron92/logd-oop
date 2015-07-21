<?php

/**
 * @file    connection.php
 * @author  Daniel   <becker_leinad@hotmail.com>
 * @date    20.07.2015
 * @package game_core
 * @subpackage database
 *
 * @description
 *
 */

class Database_Connection {

	const DRIVER_PREFIX = '\Database\Driver_';

	private function __construct() {

	}

	private function __clone()
	{}

	/**
	 * @return Database_Drivers
	 */
	public static function factory() {

		include dirname(LOGD_ROOT).'/.dbconfig.php';

		$s_class = self::DRIVER_PREFIX.DB_TYPE;
		return new $s_class;
	}

}