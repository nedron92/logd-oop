<?php defined('CORE_PATH') or die('No direct script access.');
/**
 * @file    Config.php
 * @author  Daniel Becker   <becker_leinad@hotmail.com>
 * @date    22.02.2015
 * @package game_core
 * @subpackage  LOGD
 *
 * @description
 * Configuration File of this project.
 * Enable the modules and return an array with all needed configs.
 */

class LOGD_Config
{
	/**
	 * Get an array of all configured settings
	 *
	 * @return array    hold all config settings
	 */
	public static function get_config()
	{
		return array(

			'html' => array (

				//the default standard for the 'more' section in a template
				'section_more' => array (
					'motd' => BASE_URL.'motd',
					'mail' => BASE_URL.'mail',
					'petition' => BASE_URL.'petition',
					'forum' => null,
					'chat' => null,
				),

			),
		);
	}

	/**
	 * Get only the configured modules, no more.
	 *
	 * @return mixed    get all configured modules
	 */
	public static function get_configured_modules()
	{
		return self::get_config()['modules'];
	}

	/**
	 * Return the default html array or only a specified section
	 * (useful as a fallback method)
	 *
	 * @param null|string $s_section    a specified section from the html array
	 * @return mixed (array|string)
	 */
	public static function get_default_html($s_section = null)
	{
		if(is_null($s_section)) {
			return self::get_config()['html'];
		}
		else{
			return self::get_config()['html'][$s_section];
		}
	}
}
