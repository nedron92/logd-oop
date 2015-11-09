<?php
namespace Install;
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