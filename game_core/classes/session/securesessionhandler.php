<?php
namespace session;
defined('CORE_PATH') or die('No direct script access.');

/**
 * @file    session.php
 * @author  Daniel   <becker_leinad@hotmail.com>
 * @date    13.08.2015
 * @package game_core
 * @subpackage session
 *
 * @description
 * the class to hold the session and provide methods instead of the pure array $_SESSION
 */


class SecureSessionHandler extends \SessionHandler
{
	/**
	 * @var null|string     the key to encrypt/decrypt the session data
	 */
	private $s_session_key      = 'game_session_key';
	private $s_session_name     = 'logd_game';
	private $a_cookie           =  array();
	private $s_session_file_name = "sess_";


	public function __construct()
	{
		$this->s_session_key = pack('a24',hash('sha256',$this->s_session_key));


		$this->a_cookie  = [
			'lifetime' => 0,
			'path'     => ini_get('session.cookie_path'),
			'domain'   => ini_get('session.cookie_domain'),
			'secure'   => isset($_SERVER['HTTPS']),
			'httponly' => true
		];

		$this->init();
	}

	private function init()
	{
		ini_set('session.use_cookies', 1);
		ini_set('session.use_only_cookies', 1);
		session_name($this->s_session_name);
		session_set_cookie_params(
			$this->a_cookie['lifetime'],
			$this->a_cookie['path'],
			$this->a_cookie['domain'],
			$this->a_cookie['secure'],
			$this->a_cookie['httponly']
		);
	}


	public function write($id, $data)
	{
		$data = mcrypt_encrypt(MCRYPT_3DES, $this->s_session_key, $data, MCRYPT_MODE_ECB);
		return parent::write($id, $data);
	}

	public function read($id)
	{
		$data = parent::read($id);
		$data = mcrypt_decrypt(MCRYPT_3DES, $this->s_session_key, $data, MCRYPT_MODE_ECB);

		return $data;
	}


	public function destroy($id)
	{
		if ($id === '') {
			return false;
		}

		setcookie(
			$this->s_session_name,
			'',
			time() - 42000,
			$this->a_cookie['path'],
			$this->a_cookie['domain'],
			$this->a_cookie['secure'],
			$this->a_cookie['httponly']
		);

		unlink(session_save_path().DIRECTORY_SEPARATOR.$this->s_session_file_name.$id);

		return true;

	}

}