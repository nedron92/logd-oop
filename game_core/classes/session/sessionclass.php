<?php defined('CORE_PATH') or die('No direct script access.');

/**
 * @file    sessionclass.php
 * @author  Daniel   <becker_leinad@hotmail.com>
 * @date    06.08.2015
 * @package game_core
 * @subpackage session
 *
 * @description
 * the class to hold the session and provide methods instead of the pure array $_SESSION
 */

class Session_SessionClass
{
	/**
	 * @var null|string     the current session-id
	 */
	private $s_session_id = null;

	/**
	 * @var array           this array has a reference to $_SESSION
	 */
	private $a_session_data = array();

	/**
	 * @var string          the index to store all game-needed session data
	 */
	private static $s_session_index= 'game';

	/**
	 * @var null|Session_SessionClass   it will hold the actual instance of the session
	 */
	private static $o_instance = null;

	/**
	 * constructor to set the session id and a reference to the SESSION variable
	 * Defined as private, because of singleton pattern.
	 *
	 * @param array $session    the given reference to $_SESSION global
	 */
	private function __construct(&$session)
	{
		$this->s_session_id = session_id();
		$this->a_session_data[self::$s_session_index] = &$session;
	}

	/**
	 * Defined the clone function as private, because of singleton pattern
	 */
	private function __clone()
	{}

	/**
	 * This method will return the actual session-id
	 *
	 * @return null|string
	 */
	public function get_id()
	{
		return $this->s_session_id;
	}

	/**
	 * Setting a value to the session, identified by the given key
	 * (e.q. to hold the language or something else)
	 *
	 * @param $s_key
	 * @param $s_value
	 */
	public function set_value($s_key,$s_value)
	{
		Globals::set('_SESSION',$s_value,$s_key);
	}

	/**
	 * Get the actual a value from the session by a given key
	 *
	 * @param $s_key
	 * @return mixed
	 */
	public function get_value($s_key)
	{
		return $this->a_session_data[self::$s_session_index][$s_key];
	}

	/**
	 * Method to destroy all data in the session and end it.
	 */
	public function clear_session()
	{
		Globals::set('_SESSION',array());
		$this->s_session_id = null;
		session_destroy();
	}

	/**
	 * It will create a new session instead of the old one, but CARE!
	 * It will also delete all session data.
	 *
	 * @return Sesssion
	 */
	public function create_new_session()
	{
		$this->clear_session();
		return new $this($_SESSION);
	}

	/**
	 * It will teturn the current session-object and instance
	 *
	 * @return Sesssion
	 */
	public static function get_session()
	{
		if(self::$o_instance===null)
		{
			self::$o_instance = new self($_SESSION);
		}

		return self::$o_instance;
	}
}