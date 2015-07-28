<?php

/**
 * @file    routing.php
 * @author  Daniel   <becker_leinad@hotmail.com>
 * @date    18.07.2015
 * @package game_core
 * @subpackage logd
 *
 * @description
 * This class will route all request from '?viewfile?{FILENAME}' and will render the right view.
 *
 */
class LOGD_Routing {

	private static $s_current_request_file = null;
	private static $s_current_request_path = null;

	private static $s_view_table = 'views';

	private function __construct() {
		$a_get_request = Globals::get('_GET');
		$m_view_file = null;

		if ( isset($a_get_request['viewfile']) && !is_null($a_get_request['viewfile']) ) {
			$m_view_file = explode('/',$a_get_request['viewfile']);
		}else{
			$m_view_file = 'start';
		}

		if(is_array($m_view_file)) {
			$size = sizeof($m_view_file)-1;
			self::$s_current_request_file = $m_view_file[$size];
			unset($m_view_file[$size]);
			$m_view_file = implode('/',$m_view_file);
			self::$s_current_request_path = $m_view_file;
		}
	}


	public static function init()
	{
		return new Routing();
	}

	public static function get_view()
	{
		$a_prepare_where_clause = array(
			'path' => self::$s_current_request_path,
			'name' => self::$s_current_request_file,
		);

		$result = Database::select_where(null,self::$s_view_table,$a_prepare_where_clause);

		if ( $result->success() ) {
			$a_current_view = current($result->get_entries());
			var_dump($a_current_view);
		}
	}
}