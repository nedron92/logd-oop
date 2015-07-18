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
	 * @todo set it later via database
	 */
	const LOGD_VERSION = "0.9.7 + LOGD-OOP";

	/**
	 * @const   string  the copyright of the game. DO NOT REMOVE OR CHANGE IT!!
	 * @todo append it later via database
	 */
	const LOGD_COPYRIGHT = "&copy; 2002-2003, Game: Eric Stevens, Reworking: 2015, Daniel Becker";

	/**
	 * @var  array   The including paths to search for files.
	 *               It includes at standard the GAME_PATH, CORE_PATH and MEDIA_PATH
	 */
	private static $a_paths = array(GAME_PATH, CORE_PATH, MEDIA_PATH);

	/**
	 * @var  array   Contains all loaded modules
	 * @todo set it later via database/or config
	 */
	private static $a_modules = array();

	private static $s_random_string = null;

	/**
	 * Return the seed for the number generator
	 *
	 * @return  float  the seed
	 *
	 * @copyright   The original copyright hold the developers of the original LOGD 0.9.7
	 *              (Eric Stevens etc.)
	 */
	public static function make_seed() {
		list($s_usec, $s_sec) = explode(' ', microtime());
		return (float) $s_sec + ((float) $s_usec * 100000);
	}

	/**
	 * Searches for a file at the filesystem of the game
	 *
	 * @param   string  $s_directory  directory name
	 * @param   string  $s_file       filename with subdirectory
	 * @param   string  $s_extension  extension to search for
	 * @return  string|bool         Returns FALSE if the file is not found, otherwise the path
	 *
	 * @copyright   The original copyright hold the developers of the Kohana PHP frameworks.
	 *              This method is a modified one.
	 */
	public static function find_file($s_directory, $s_file, $s_extension = NULL)
	{
		if(Cache::getInstance()->exists('file_'.$s_file))
			return Cache::getInstance()->get('file_'.$s_file);

		if(is_null($s_extension)) $s_extension = EXT;
		else ($s_extension) ? $s_extension = ".{$s_extension}" : $s_extension = '';

		// Create a partial path of the filename
		$path = $s_directory.DIRECTORY_SEPARATOR.$s_file.$s_extension;

		// The file has not been found yet
		$m_found = FALSE;

		foreach (self::$a_paths as $directory)
		{
			if (is_file($directory.$path))
			{
				// A path has been found, so set it and stop searching
				$m_found = $directory.$path;

				// if file was found and isn't cached, then cached it for one-day
				if(!Cache::getInstance()->exists('file_'.$s_file))
					Cache::getInstance()->set("file_".$s_file,$m_found , 86400);
				break;
			}
		}

		return $m_found;
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

	/**
	 * Create a random string for append at the uri
	 *
	 * @return string return the unique/random string
	 */
	public static function create_random_uri_string()
	{
		if ( is_null(self::$s_random_string) ) {
			if(is_null($_SERVER['UNIQUE_ID']) || $_SERVER['UNIQUE_ID'] === '')
			{
				$s_random = str_shuffle(PHP_OS.time());
				$s_random_string = str_shuffle(base64_encode($s_random));
			}else{
				$s_random = str_shuffle($_SERVER['UNIQUE_ID'].time());
				$s_random_string = str_shuffle(base64_encode($s_random));
			}

			self::$s_random_string = $s_random_string;
		}

		return self::$s_random_string;
	}

} 