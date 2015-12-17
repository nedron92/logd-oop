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

//define a array with needed path values
 $a_include_paths = array(
	'module_path' => MOD_PATH.PATH_SEPARATOR,
	'core_path' => CORE_PATH.'classes'.DIRECTORY_SEPARATOR.PATH_SEPARATOR,
	'game_path' => GAME_PATH.'classes'.DIRECTORY_SEPARATOR.PATH_SEPARATOR,
);

//iterate over this array to add the include paths correctly
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


//Clear complete cache if needed.
//Cache::getInstance()->clear();
//die;

//Initialize the random number generator
mt_srand(LOGD::make_seed());

//Initialize the Session and the Routing
Session::get_session();
Routing::init();

//check if the dbconfig.default.php exist, if yes - we have to install the game
if( !file_exists(dirname(LOGD_ROOT).DIRECTORY_SEPARATOR.'.dbconfig'.EXT) &&
     file_exists(dirname(LOGD_ROOT).DIRECTORY_SEPARATOR.'.dbconfig.default'.EXT) )
	{
		//try-catch the installer, if it fails
		try {
			new \Install\Installer();
		}catch (LOGD_Exception $e) {
			$e->print_error();
		}
		exit (1);
	}

//Initialize the language Class
//todo this is later only a fallback if nothing found in the database
//I18N::init(GAME_LANGUAGE);

//including the .dbconfig.php with all Database-Constants
include dirname(LOGD_ROOT).DIRECTORY_SEPARATOR.'.dbconfig'.EXT;




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

