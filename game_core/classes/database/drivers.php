<?php

/**
 * @file    drivers.php
 * @author  Daniel   <becker_leinad@hotmail.com>
 * @date    20.07.2015
 * @package ${PACKAGE}
 * @subpackage ${SUBPACKAGE}
 *
 * @description
 *
 */

abstract class Database_Drivers {

	abstract public function __construct();

	public function get_driver_name() {
		return get_class($this);
	}



}