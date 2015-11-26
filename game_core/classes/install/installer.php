<?php
namespace install;
defined('CORE_PATH') or die('No direct script access.');
/**
 * @file    installer.php
 * @author  Daniel   <becker_leinad@hotmail.com>
 * @date    05.08.2015
 * @package game_core
 * @subpackage install
 *
 * @description
 * the installer class for the game
 */
class Installer {

	/**
	 * @var int     the counter which step will be the next one and on which step we are
	 */
	private $i_step = 0;

	/**
	 * @var string  hold the title of the current install-step
	 */
	private $s_step_title;


	/**
	 * the constructor of this installer-class.
	 * it initialize all needed values and set the hidden fields for the template
	 */
	public function __construct()
	{
		//Initialize the installer with the current-needed POST and Session values
		$this->init();

		//set a common title
		$this->s_step_title = __('install_common_title','install');

		//set the hidden fields, which we don't need for the installation
		\Replacer::set_hidden_fields(array('character','online','more'));

		if ($this->check_dbconfig_file() !== array()) {
			\Replacer::page_header($this->s_step_title);
			$message = __('Probleme mit der ".dbconfig.default'.EXT.'". <br>Folgende Konstanten haben andere Werte als die ursprüngliche Datei');
			throw new \LOGD_Exception($message);
		}
		//switch/case for the step and go to the right method
		switch($this->i_step)
		{
			case 0:
			{
				$this->step_0();
				break;
			}
		}

		//set the page header and render the install view
		\Replacer::page_header($this->s_step_title);

		\View::create('install')
			->bind_by_value('i_step',$this->i_step)
			->render();
	}

	/**
	 * This method initialize the current language and the current step
	 *
	 */
	private function init()
	{
		//get the current post-request and check if the language was given by POST
		$s_post = \Globals::get('_POST');
		if (!is_null($s_post['game_language'])) {
			\Session::get_session()->set_value('language',$s_post['game_language']);
		}

		//Initialize the I18N-Class for templates-usage
		\I18N::init();

		//if we have no language-value in the session, set the default install-language to ENGLISH
		if (is_null(\Session::get_session()->get_value('language'))) {
			\I18N::set_language('en_EN');
		}else{
			\I18N::set_language(\Session::get_session()->get_value('language'));
		}

		\Replacer::set_language(\I18N::get_language());

		//set the current step by POST
		$this->i_step = $s_post['install_step'];
	}

	private function check_dbconfig_file()
	{
		$s_file = file_get_contents(dirname(LOGD_ROOT).DIRECTORY_SEPARATOR.'.dbconfig.default'.EXT);

		$a_constant_value_mapping = array();
		$a_failure_array = array();

		while(1)
		{
			static $counter = 0;
			static $s_first_const = 0;
			static $s_first_const_end = 0;

			$s_first_const = strpos($s_file,'const ',$s_first_const_end);
			if (!$s_first_const) break;
			$s_first_const_end = strpos($s_file,';',$s_first_const);
			$length = $s_first_const_end - $s_first_const;
			$s_current_substr = substr($s_file,$s_first_const,$length);

			$key = trim(substr($s_current_substr,6, strpos($s_current_substr,'=') - 7 ) );
			$value = trim(substr($s_current_substr, strpos($s_current_substr,'=') + 3 ), ' \'');
			$a_constant_value_mapping[$key] = $value;
		}

		foreach($a_constant_value_mapping as $key => $value)
		{
			if ($value === '%%' . $key . '%%') {
				continue;
			}else{
				$a_failure_array[] = $key;
			}
		}

		return $a_failure_array;

	}


	/**
	 * Method for the installation-step-0
	 */
	private function step_0()
	{
		\Replacer::addnav('Steps');
		\Replacer::addnav(__('install_common_step','install').' 0 '.__('install_common_title_step_0','install'),BASE_URL);
		\Replacer::addnav(__('install_common_step','install').' 1 '.__('install_common_title_step_0','install'),BASE_URL);
		$this->s_step_title.= __('install_common_title_step_0','install');
	}
}