<?php defined('CORE_PATH') or die('No direct script access.');

/**
 * @file    Core.php
 * @author  Daniel Becker   <becker_leinad@hotmail.com>
 * @date    19.02.2015
 * @package game_core
 * @subpackage  LOGD
 *
 * @description
 * That is the main class of the game
 */

class LOGD_Core
{
	/**
	 * @const  string   The Version of the Game
	 */
	const LOGD_VERSION = "0.9.7 + Kuria Edition";

	/**
	 * @const   string  the copyright of the game. DO NOT REMOVE OR CHANGE IT!!
	 */
	const LOGD_COPYRIGHT = "&copy; 2002-2003, Game: Eric Stevens, Reworking: 2015, Daniel Becker";

	/**
	 * @var  array   The including paths to search for files.
	 *               It includes at standard the GAME_PATH, CORE_PATH and MEDIA_PATH
	 */
	private static $a_paths = array(GAME_PATH, CORE_PATH, MEDIA_PATH);

	/**
	 * @var  array   Contains all loaded modules
	 */
	private static $a_modules = array();

	/**
	 * Return the the seed for the number generator
	 *
	 * @return  float  the seed
	 *
	 * @copyright   The original copyright hold the developers of the original LOGD 0.9.7
	 *              (Eric Stevens etc.)
	 */
	public static function make_seed() {
		list($usec, $sec) = explode(' ', microtime());
		return (float) $sec + ((float) $usec * 100000);
	}

	/**
	 * The autoloader function to load the required class
	 * Its according to the PSR-0 standard
	 *
	 * This LOGD-Game has following names convention of the classes:
	 *
	 *      All Classes have to be named relative to the $directory here
	 *      (each directory is an underscore at the Class name)
	 *          for example:
	 *              Class Name: LOGD_CORE
	 *              Meaning:    $directory/LOGD/Core.EXTENSION
	 *                          if the autoloader search for it
	 *
	 *      If you have a same named class in the game directory, then that class will be loaded
	 *      (and it were usefull if this Class extends the Core-Classes).
	 *
	 * @param   string  $class      name of the class
	 * @param   string  $directory  directory name in which the classes are located
	 * @return  bool    TRUE if the class could loaded and false otherwise
	 *
	 * @copyright   The original copyright hold the developers of the Kohana PHP frameworks.
	 *              This method is a modified one.
	 */
	public static function auto_load($class,$directory = 'classes')
	{
		if(Cache::getInstance()->exists('class_'.$class))
		{
			require_once Cache::getInstance()->get('class_'.$class);
			return TRUE;
		}

		// Transform the class name according to PSR-0
		$class     = ltrim($class, '\\');
		$file      = '';
		$namespace = '';

		if ($last_namespace_position = strripos($class, '\\'))
		{
			$namespace = substr($class, 0, $last_namespace_position);
			$class     = substr($class, $last_namespace_position + 1);
			$file      = str_replace('\\', DIRECTORY_SEPARATOR, $namespace).DIRECTORY_SEPARATOR;
		}

		$file .= str_replace('_', DIRECTORY_SEPARATOR, $class);

		if ($path = self::find_file($directory, $file))
		{
			if(!Cache::getInstance()->exists('class_'.$class))
				Cache::getInstance()->set("class_".$class,$path , 86400);
			// Load the class file and return true
			require_once $path;
			return TRUE;
		}

		// Class not found, so return false
		return FALSE;
	}

	/**
	 * Searches for a file at the filesystem of the game
	 *
	 * @param   string  $directory  directory name
	 * @param   string  $file       filename with subdirectory
	 * @param   string  $extension  extension to search for
	 * @return  string|bool         Returns FALSE if the file is not found, otherwise the path
	 *
	 * @copyright   The original copyright hold the developers of the Kohana PHP frameworks.
	 *              This method is a modified one.
	 */
	public static function find_file($directory, $file, $extension = NULL)
	{
		if(Cache::getInstance()->exists('file_'.$file))
			return Cache::getInstance()->get('file_'.$file);

		if(is_null($extension)) $extension = EXT;
		else ($extension) ? $extension = ".{$extension}" : $extension = '';

		// Create a partial path of the filename
		$path = $directory.DIRECTORY_SEPARATOR.$file.$extension;

		// The file has not been found yet
		$found = FALSE;

		foreach (self::$a_paths as $directory)
		{
			if (is_file($directory.$path))
			{
				// A path has been found, so set it and stop searching
				$found = $directory.$path;

				// if file was found and isn't cached, then cached it for one-day
				if(!Cache::getInstance()->exists('file_'.$file))
					Cache::getInstance()->set("file_".$file,$found , 86400);
				break;
			}
		}

		return $found;
	}

	/**
	 * Load the modules specified with an array.
	 * Also change the PATH member to find the classes within the modules
	 *
	 * @param   array|null  $modules  the modules array
	 * @return  array   return the loaded modules
	 * @throws  LOGD_Exception   throw Exception if the module was not found
	 *
	 * @copyright   The original copyright hold the developers of the Kohana PHP frameworks.
	 *              This method is a modified one.
	 */
	public static function load_modules(array $modules = null)
	{
		//todo: load modules from database

	}

} 