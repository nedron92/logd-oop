<?php defined('CORE_PATH') or die('No direct script access.');
/**
 * @file    bootstrap.php
 * @author  Daniel Becker   <becker_leinad@hotmail.com>
 * @date    26.02.2015
 * @package game
 *
 * @description
 * bootstrap file for the game
 *
 * @copyright   The original copyright hold the developers of the Kohana PHP frameworks.
 *              This file is a modified one of the Kohana bootstrap.php.
 */

// -- Environment setup --------------------------------------------------------

// Load the core of LOGD and the Cache-Class
require CORE_PATH.'classes/LOGD/Core'.EXT;
require CORE_PATH.'classes/LOGD/Cache'.EXT;

if (is_file(GAME_PATH.'classes/LOGD'.EXT))
{
	// Game extends the core
	require GAME_PATH.'classes/LOGD'.EXT;
	require GAME_PATH.'classes/Cache'.EXT;
}
else
{
	// Load empty core extension
	require CORE_PATH.'classes/LOGD'.EXT;
	require CORE_PATH.'classes/Cache'.EXT;
}

/**
 * Set the default time zone.
 * @link http://www.php.net/manual/timezones
 */
date_default_timezone_set('Europe/Berlin');

/**
 * Set the default locale.
 * @link http://www.php.net/manual/function.setlocale
 */
setlocale(LC_ALL, 'en_EN.utf-8');

/**
 * Enable the LOGD auto-loader.
 *
 * @link http://www.php.net/manual/function.spl-autoload-register
 */
spl_autoload_register(array('LOGD', 'auto_load'));

//Initialize the random number generator
mt_srand(LOGD::make_seed());

//Clear complete cache if needed.
	//Cache::getInstance()->clear();
	//die;


//Load the default template instance
Template::get_instance(null,false);

//Set the page_header and page_footer every time
Replacer::page_header('LOGD - OOP');
Replacer::page_footer();

/**
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
View::render('start');
