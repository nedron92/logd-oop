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
 * Set the default locale.
 * @todo set it later via database
 */
setlocale(LC_ALL, 'en_EN.utf-8');

/*
 * Enable the auto-loader.
 * it will be accept namespace-based classes and also classes
 * with underscores. It will replace the underscores as path to the class.
 */

spl_autoload_register(function($class) {
	spl_autoload(str_replace('_', DIRECTORY_SEPARATOR, $class));
});

//Initialize the random number generator
mt_srand(LOGD::make_seed());

//Clear complete cache if needed.
	//Cache::getInstance()->clear();
	//die;


//Load the default template instance
// @todo maybe set it later via database
//Template::get_instance(null,false);

//Set the page_header and page_footer every time
// @todo set it later via database
Replacer::page_header('LOGD - OOP');

/*
 * Try to load all configured modules
 */
/*try{
	LOGD::load_modules(Config::get_configured_modules());
}catch (LOGD_Exception $e)
	{ $e->print_error(); }
*/

//todo: implement routing

$a_tables = array('accounts','test');
$a_alias  = array('acc','test');

$a_mapping = array(
	'acc.acct_id' => '1',
	'test.id' => '1',
);

/*
$result = Database::delete($a_tables,$a_alias,$a_mapping);
if($result->success())
{
	Replacer::output('You registered successfully. Enjoy the Game');
}else{
	Replacer::output('You registered successfully. Enjoy the Game2');
}
*/

//Render the first view
View::create('start')->render();

