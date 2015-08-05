<?php defined('CORE_PATH') or die('No direct script access.');
/**
 * @file    bootstrap.php
 * @author  Daniel Becker   <becker_leinad@hotmail.com>
 * @date    26.02.2015
 * @package game
 *
 * @description
 * bootstrap file for the game
 */

// ---------------SET UP ----------------

//define a static array with needed path values

static $a_include_paths = array();
 $a_include_paths = array(
	'module_path' => MOD_PATH.PATH_SEPARATOR,
	'core_path' => CORE_PATH.'classes'.DIRECTORY_SEPARATOR.PATH_SEPARATOR,
	'game_path' => GAME_PATH.'classes'.DIRECTORY_SEPARATOR.PATH_SEPARATOR,
);

foreach($a_include_paths as $value)
{ set_include_path($value.get_include_path()); }


/*
 * Set the default time zone.
 * @todo set it later via database
 */
date_default_timezone_set('Europe/Berlin');

/*
 * Enable the auto-loader.
 * it will be accept namespace-based classes and also classes
 * with underscores. It will replace the underscores as path to the class.
 */

spl_autoload_register(function($class) {
	spl_autoload(str_replace('_', DIRECTORY_SEPARATOR, $class));
});

//Initialize the language Class
//todo this is later only a fallback if nothing found in the database
I18N::init(GAME_LANGUAGE);

//Initialize the random number generator
mt_srand(LOGD::make_seed());

if( !file_exists(dirname(LOGD_ROOT).DIRECTORY_SEPARATOR.'.dbconfig.php') &&
     file_exists(dirname(LOGD_ROOT).DIRECTORY_SEPARATOR.'.dbconfig.default.php') )
	{
		View::create('install')->render();
		exit (1);
	}

//including the .dbconfig.php with all Database-Constants
include dirname(LOGD_ROOT).DIRECTORY_SEPARATOR.'.dbconfig'.EXT;

//Clear complete cache if needed.
	//Cache::getInstance()->clear();
	//die;


//Load the default template instance
// @todo maybe set it later via database
//Template::get_instance(null,false);

//Set the page_header and page_footer every time
// @todo set it later via database
Replacer::page_header('LOGD - OOP');

$test = Database::factory();
//todo: implement routing
//Routing::init()->get_view();

//Render the first view
View::create('start')->render();

