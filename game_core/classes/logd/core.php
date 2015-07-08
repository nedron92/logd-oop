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
	 */
	public static function load_modules(array $modules = null)
	{
		//todo: load modules from database

	}

} 