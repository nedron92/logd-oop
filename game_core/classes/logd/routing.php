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
class LOGD_Routing
{
	private  $s_current_request_path = null;
	private  $s_current_request_file = null;
	private  $s_view_table = 'views';
	private  $a_routing_table = array();

	private function __construct()
	{
		$this->set_routing_table();
		$a_get_request = Globals::get('_GET');
		$m_route = array();

		if ( isset($a_get_request['viewfile']) && !is_null($a_get_request['viewfile']) ) {
			$m_route = explode('/',$a_get_request['viewfile']);
		}else{
			$m_route[0] = 'start';
		}

		$a_route = $this->get_route($m_route[0]);

		if ($a_route !== false) {
			if($a_route['type'] === 'class')
			{
				try
				{
					$o_reflect_class  = new ReflectionClass($m_route[0].'\\'.$a_route['file']);

					if ($o_reflect_class->hasMethod($m_route[1]))
					{
						if ($m_route[0] === 'ajax')
						{
							$o_reflect_class->newInstance($m_route[1]);
							exit(1);
						}else{
							$o_reflect_method = $o_reflect_class->getMethod($m_route[1]);

							if ($o_reflect_method->isStatic()) {
								$o_reflect_method->invoke(null);
							}else{
								$o_reflect_method->invoke($o_reflect_class->newInstance());
							}
						}
					}

				}catch (ReflectionException $e)
				{
					try
					{
						$o_reflect_class  = new ReflectionClass($m_route[0].'\\'.$a_route['file']);

						if ($o_reflect_class->hasMethod($a_route['default_method']))
						{
							$o_reflect_method = $o_reflect_class->getMethod($a_route['default_method']);

							if ($o_reflect_method->isStatic()) {
								$o_reflect_method->invoke(null);
							}else{
								$o_reflect_method->invoke($o_reflect_class->newInstance());
							}
						}

					}catch (ReflectionException $e)
					{
						echo $e->getMessage();
					}

				}
			}
		}
		/*
		$size = sizeof($m_view_file)-1;
		self::$s_current_request_file = $m_view_file[$size];
		unset($m_view_file[$size]);
		$m_view_file = implode('/',$m_view_file);
		self::$s_current_request_path = $m_view_file;
		*/
	}


	/**
	 * @return Routing
	 */
	public static function init()
	{
		return new Routing();
	}

	public function get_view()
	{
		/*
		$a_prepare_where_clause = array(
			'path' => self::$s_current_request_path,
			'name' => self::$s_current_request_file,
		);

		//$result = Database::select_where(null,self::$s_view_table,$a_prepare_where_clause);

		if ( $result->success() ) {
			$a_current_view = current($result->get_entries());
			var_dump($a_current_view);
		}
		*/
	}

	private function set_routing_table()
	{
		$this->a_routing_table['ajax'] = array(
			'type' => 'class',
			'file' => 'ajaxhandler',
			'default_method' => 'fallback'
		);

		$this->a_routing_table['install'] = array(
			'type' => 'class',
			'file' => 'installer',
			'default_method' => 'index'
		);

	}

	public function get_route($s_route_name)
	{

		if(isset($this->a_routing_table[$s_route_name]))
		{
			return $this->a_routing_table[$s_route_name];
		}else{
			return false;
		}

	}
}