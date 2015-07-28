<?php defined('CORE_PATH') or die('No direct script access.');

/**
 * Created by PhpStorm.
 * User: nedron
 * Date: 28.07.15
 * Time: 17:34
 */
class LOGD_I18N
{

	private static $s_language_code = 'en_EN';
	const LANGUAGE_PATH = CORE_PATH.'i18n'.DIRECTORY_SEPARATOR;
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

	public static function translate($s_translate_id)
	{
		if(sizeof(self::$a_language_strings) === 0 || sizeof(self::$a_language_strings[self::$s_language_code]) === 0) {
			$s_subdir = self::$s_language_code.DIRECTORY_SEPARATOR;
			$s_language_file = self::$s_language_code.EXT;
			self::$a_language_strings[self::$s_language_code] = include_once self::LANGUAGE_PATH.$s_subdir.$s_language_file;
		}

		if(is_null(self::$a_language_strings[self::$s_language_code][$s_translate_id]) ||
			self::$a_language_strings[self::$s_language_code][$s_translate_id] === '')
		{
			return $s_translate_id;
		}else{
			return self::$a_language_strings[self::$s_language_code][$s_translate_id];
		}
	}

	public static function clear_language_array(){
		self::$a_language_strings = array();
	}
}

if ( ! function_exists('__'))
{
	function __($s_translate_id)
	{
		return LOGD_I18N::translate($s_translate_id);
	}
}