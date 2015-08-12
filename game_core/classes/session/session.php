<?php
namespace session;
defined('CORE_PATH') or die('No direct script access.');
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

class Session
{
	/**
	 * @var array           this array has a reference to $_SESSION
	 */
	private $a_session_data = array();

	/**
	 * @var string          the index to store all game-needed session data
	 */
	private static $s_session_index= 'game';

	/**
	 * @var null|Session   it will hold the current instance of the session
	 */
	private static $o_instance = null;

	/**
	 * Constructor which initialize the the secure-session-handler and
	 * start the session with a new generated id.
	 * Defined as private, because of singleton pattern.
	 *
	 */
	private function __construct()
	{
		$session_handler = new SecureSessionHandler;
		session_set_save_handler($session_handler,true);
		session_start();
		$this->a_session_data[self::$s_session_index] = &$_SESSION;
		$this->refresh_session();
	}

	/**
	 * Defined the clone function as private, because of singleton pattern
	 */
	private function __clone()
	{}

	/**
	 * It will return the current session-object and instance
	 *
	 * @return Session
	 */
	public static function get_session()
	{
		if(self::$o_instance===null)
		{
			self::$o_instance = new self();
		}

		return self::$o_instance;
	}

	/**
	 * This method will generate a new session id and will delete the old one
	 *
	 * @return bool     Value if regenerate was successful or not
	 */
	public function refresh_session()
	{
		return session_regenerate_id(true);
	}

	/**
	 * Method to destroy all data in the session and end it.
	 */
	public function clear_session()
	{
		\Globals::set('_SESSION',array());
		session_destroy();
	}

	/**
	 * It will create a new session instead of the old one, but CARE!
	 * It will also delete all session data.
	 *
	 * @return Session
	 */
	public function create_new_session()
	{
		$this->clear_session();
		return new $this();
	}

	/**
	 * This method will return the current session-id
	 *
	 * @return null|string
	 */
	public function get_id()
	{
		return session_id();
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
		$this->a_session_data[self::$s_session_index][$s_key] = $s_value;
	}

	/**
	 * Get the current value from the session by a given key
	 *
	 * @param $s_key
	 * @return mixed
	 */
	public function get_value($s_key)
	{
		return $this->a_session_data[self::$s_session_index][$s_key];
	}

	/**
	 * This method checks if the current session is expired or not.
	 * With the given parameter the lifetime will be set.
	 *
	 * @param int $i_time_to_live   the number of minutes how long the session is a valid one
	 * @return bool                 return true if sessions is expired or false if its valid
	 */
	public function is_session_expired($i_time_to_live = 30)
	{
		$m_last_time = false;
		if (isset($this->a_session_data['_last_activity'])) {
			$m_last_time = $this->a_session_data['_last_activity'];
		}

		if ( ($m_last_time !== false) && (time() - $m_last_time > $i_time_to_live * 60) ) {
			return true;
		}

		$this->a_session_data['_last_activity'] = time();
		return false;
	}

	/**
	 * This method checks if the session has a valid fingerprint or not, based on the
	 * user-agent, the remote ip-address and a normal subnet-mask
	 *
	 * @return bool     return TRUE if the fingerprint is valid and FALSE if not
	 */
	public function is_session_fingerprint_valid()
	{
		$a_server = \Globals::get('_SERVER');
		$hash = md5($a_server['HTTP_USER_AGENT'] . (ip2long($a_server['REMOTE_ADDR']) & ip2long('255.255.0.0')) );

		if (isset($this->a_session_data['_fingerprint'])) {
			if ($this->a_session_data['_fingerprint'] === $hash) {
				return true;
			}else{
				return false;
			}
		}

		$this->a_session_data['_fingerprint'] = $hash;
		return true;
	}

	/**
	 * Checking if the session is a valid one (if not expired and has the current correct fingerprint)
	 *
	 * @return bool     return TRUE, if session is valid one and FALSE if not
	 */
	public function is_session_valid()
	{
		return ( (!$this->is_session_expired()) && ($this->is_session_fingerprint_valid()) );
	}
}