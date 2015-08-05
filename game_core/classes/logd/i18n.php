<?php defined('CORE_PATH') or die('No direct script access.');
/**
 * @file    Replacer.php
 * @author  Daniel Becker   <becker_leinad@hotmail.com>
 * @date    28.07.2015
 * @package game_core
 * @subpackage LOGD
 *
 * @description
 * This class provide methods for translations in all supported languages
 */

class LOGD_I18N
{
	/**
	 * @var null|string     The language code (e.g. de_DE/en_EN)
	 */
	private static $s_language_code = null;

	/**
	 * @var null|string     the path to the language, will be calculated on ::init
	 */
	private static $s_language_path = null;

	/**
	 * @var array           this array contains all current language strings
	 */
	private static $a_language_strings = array();

	/**
	 * @var array           the array contains all supported languages (key = code, value = Language-Name)
	 */
	private static $a_supported_languages = array();

	/**
	 * Defined the constructor as private, because of no need for it.
	 */
	private function __construct()
	{ }

	/**
	 * Defined the clone function as private, because of no need for it.
	 */
	private function __clone()
	{}

	public static function init()
	{
		if(is_null(self::$s_language_path)) {
			self::$s_language_path = CORE_PATH.'i18n'.DIRECTORY_SEPARATOR;
		}
	}

	public static function set_language($s_language_code = '')
	{
		if(!is_null($s_language_code) && !empty($s_language_code)) {
			self::$s_language_code = $s_language_code;
		}
	}

	public static function get_language()
	{
		return self::$s_language_code;
	}

	public static function get_language_array($s_section=null)
	{
		if(is_null($s_section)) {
			return self::$a_language_strings;
		}else{
			return self::$a_language_strings[self::$s_language_code.'.'.$s_section];

		}
	}

	public static function translate($s_translate_id,$s_section='common')
	{
		//set up the actual language with section
		$s_language_section = self::$s_language_code.'.'.$s_section;

		if(sizeof(self::$a_language_strings) === 0 || sizeof(self::$a_language_strings[$s_language_section]) === 0) {
			$s_subdir = self::$s_language_code.DIRECTORY_SEPARATOR.$s_section.DIRECTORY_SEPARATOR;
			$s_language_file = self::$s_language_code.EXT;
			self::$a_language_strings[$s_language_section] = include_once self::$s_language_path.$s_subdir.$s_language_file;
		}

		if(is_null(self::$a_language_strings[$s_language_section][$s_translate_id]) ||
			self::$a_language_strings[$s_language_section][$s_translate_id] === '')
		{
			return $s_translate_id;
		}else{
			return self::$a_language_strings[$s_language_section][$s_translate_id];
		}
	}

	/**
	 *
	 */
	public static function clear_language_array(){
		self::$a_language_strings = array();
	}

	/**
	 * @param bool $b_from_database
	 * @return array
	 */
	public static function get_all_supported_languages($b_from_database=true)
	{
		if(self::$a_supported_languages === array())
		{
			self::$a_supported_languages = self::mapping_language_codes_to_names($b_from_database);
			return self::$a_supported_languages;
		}else{
			return self::$a_supported_languages;
		}
	}

	private static function get_all_languages_codes()
	{
		return include self::$s_language_path.'languages_codes'.EXT;
	}

	private static function get_implemented_languages($b_from_database)
	{
		if (!$b_from_database)
		{
			self::init();
			$a_dir_entries = scandir(self::$s_language_path);
			$a_supported_languages = array();
			chdir(self::$s_language_path);
			foreach($a_dir_entries as $s_entry)
			{
				if($s_entry === '.' || $s_entry === '..') continue;
				if(is_dir($s_entry))
				{
					$a_supported_languages[] = $s_entry;
				}
			}
			chdir(LOGD_ROOT);
			return $a_supported_languages;
		}else{
			//todo get from database later
			return null;
		}
	}

	private static function mapping_language_codes_to_names($b_from_database)
	{
		$a_supported_languages = self::get_implemented_languages($b_from_database);
		$a_mapping_languages = array();

		//todo maybe store in database later or store name in table with code
		$a_all_languages_codes = self::get_all_languages_codes();

		foreach($a_supported_languages as $s_supported_language)
		{
			$s_supported_language_fallback = strstr($s_supported_language,'_',true);
			if(array_key_exists($s_supported_language,$a_all_languages_codes) || array_key_exists($s_supported_language_fallback,$a_all_languages_codes))
			{
				if(!is_null($a_all_languages_codes[$s_supported_language])) {
					$a_mapping_languages[$s_supported_language] = $a_all_languages_codes[$s_supported_language];
				}else{
					$a_mapping_languages[$s_supported_language] = $a_all_languages_codes[$s_supported_language_fallback];

				}
			}
		}

		return $a_mapping_languages;
	}
}

if ( ! function_exists('__'))
{
	function __($s_translate_id,$s_section='common')
	{
		return I18N::translate($s_translate_id,$s_section);
	}
}