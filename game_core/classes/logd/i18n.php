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
	 * Defined the constructor as private, because of no need for it.
	 */
	private function __construct()
	{ }

	/**
	 * Defined the clone function as private, because of no need for it.
	 */
	private function __clone()
	{}

	public static function init($s_language)
	{
		self::set_language($s_language);
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

	public static function clear_language_array(){
		self::$a_language_strings = array();
	}
}

if ( ! function_exists('__'))
{
	function __($s_translate_id,$s_section='common')
	{
		return I18N::translate($s_translate_id,$s_section);
	}
}