<?php
namespace session;
defined('CORE_PATH') or die('No direct script access.');

/**
 * @file    securesessionhandler.php
 * @author  Daniel   <becker_leinad@hotmail.com>
 * @date    06.11.2015
 * @package game_core
 * @subpackage session
 *
 * @description
 * This class define a secure handler based on the standard php-session handler.
 */


class SecureSessionHandler extends \SessionHandler
{
	/**
	 * @var null|string     the key to encrypt/decrypt the session data
	 */
	private $s_session_key      = 'game_session_key';
	private $s_session_name     = 'logd_game';
	private $a_cookie           =  array();

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


		setcookie( $this->s_session_name, null, time() - 42000);


		parent::destroy($id);

		return true;

	}

}