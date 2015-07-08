<?php
/**
 * @file    index.php
 * @author  Daniel Becker   <becker_leinad@hotmail.com>
 * @date    26.02.2015
 *
 * @description
 * Start file of this project
 *
 * @copyright   The original copyright hold the developers of the Kohana PHP frameworks.
 *              This file is a modified one of the Kohana index.php.
 */

//compress every time the output with gzip
if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler");
//set the PageStart time and start/reload the session each time
define('PAGE_START_TIME',microtime(true));
session_start();

/**
 * The directory in which your own application/game files are
 * located. (For example to extend a Core Class or create new Classes).
 * The game directory must contain the bootstrap.php file.
 */
$game = '../game';

/**
 * The directory in which all core files of LOGD are located.
 */
$game_core = '../game_core';

/**
 * The directory in which additional modules are located to extend
 * the game.
 */
$modules = '../modules';

/**
 * The directory in which additional media (e.q. all templates/css etc.) are located
 */
$media = 'media';

/**
 * The default extension of all your files. If you change that, you have to
 * rename all files to the new extension.
 */
define('EXT', '.php');

/**
 * Set the PHP error reporting level.
 */
error_reporting(E_ALL ^ E_NOTICE);

/**
 * Change the Code below only if you are a developer and know enough about
 * OOP and such stuff with LOGD.
 */

// Set the full path to the LOGD ROOT (in this case the public directory)
define('LOGD_ROOT', realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR);

// Make the game_core directory relative to the LOGD ROOT, for symlink'd index.php
if ( ! is_dir($game_core) AND is_dir(LOGD_ROOT.$game_core))
	$game_core = LOGD_ROOT.$game_core;

// Make the game directory relative to the LOGD ROOT, for symlink'd index.php
if ( ! is_dir($game) AND is_dir(LOGD_ROOT.$game))
	$game = LOGD_ROOT.$game;

// Make the modules directory relative to the LOGD ROOT, for symlink'd index.php
if ( ! is_dir($modules) AND is_dir(LOGD_ROOT.$modules))
	$modules = LOGD_ROOT.$modules;

// Define the absolute paths for configured directories
define('CORE_PATH', realpath($game_core).DIRECTORY_SEPARATOR);
define('GAME_PATH', realpath($game).DIRECTORY_SEPARATOR);
define('MOD_PATH', realpath($modules).DIRECTORY_SEPARATOR);
define('MEDIA_PATH', realpath($media).DIRECTORY_SEPARATOR);

// Clean up the variables
unset($game_core, $game, $modules);

/**
 * Get the Base URL for LOGD
 */
$document_root = $_SERVER['DOCUMENT_ROOT'];

if(PHP_OS === "WINNT")
{ $document_root = str_replace("/",DIRECTORY_SEPARATOR,$_SERVER['DOCUMENT_ROOT']); }
$document_root = str_replace($document_root,NULL,LOGD_ROOT);
if(PHP_OS === "WINNT")
{ $document_root = str_replace(DIRECTORY_SEPARATOR,"/",$document_root); }

/**
 * This is a workaround for my own-server, because of some linux-problems.
 * Comment it out, if you don't need it. (c) Daniel Becker
 */
if(PHP_OS === 'Linux')
{
	$document_root = str_replace('home/','~',$document_root);
	$document_root = str_replace('public_html/',null,$document_root);
}

define('BASE_URL', $document_root);
define('MEDIA_URL', BASE_URL.'/'.basename(MEDIA_PATH).'/');

// Bootstrap the game
require GAME_PATH.'bootstrap'.EXT;
