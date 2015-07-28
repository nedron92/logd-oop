<?php defined('CORE_PATH') or die('No direct script access.');
/**
 * @file    .dbconfig.php
 * @author  Daniel Becker   <becker_leinad@hotmail.com>
 * @date    26.03.2015
 *
 * @description
 * This file hold all needed database configuration constants
 */

//the database-type (mysql, sql-lite etc.).
//Look at game_core/database/drivers for all implemented database-drivers
const DB_TYPE = 'mysql';

//a prefix of all table names (if you changed the table-name-structure
//you have to edit this)
const DB_PREFIX = 'logd_';

//your connection settings for the database
// Host/Database-Name/Database-User and your password
const DB_HOST = 'localhost';
const DB_NAME = 'logd-oop';
const DB_USER = 'root';
const DB_PASSWD = '';
