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

	private $i_step = 0;
	private $s_step_title;


	public function __construct()
	{
		//Initialize the installer with the current-needed POST and Session values
		$this->init();

		$this->s_step_title = __('install_common_title','install');

		\Replacer::set_hidden_fields(array('character','online','more'));

		switch($this->i_step)
		{
			case 0:
			{
				$this->step_0();
				break;
			}
		}

		 \Replacer::page_header($this->s_step_title);

		\View::create('install')
			->bind_by_value('i_step',$this->i_step)
			->render();
	}

	private function init()
	{
		//get the actual post-request and check if the language was given by POST
		$s_post = \Globals::get('_POST');
		if (!is_null($s_post['game_language'])) {
			\Sesssion::get_session()->set_value('language',$s_post['game_language']);
		}

		//Initialize the I18N-Class for templates-usage
		\I18N::init();

		//if we have no language-value in the session, set the default install-language to ENGLISH
		if (is_null(\Sesssion::get_session()->get_value('language'))) {
			\I18N::set_language('en_EN');
		}else{
			\I18N::set_language(\Sesssion::get_session()->get_value('language'));
		}

		//set the current step by POST
		$this->i_step = $s_post['install_step'];
	}

	private function step_0()
	{
		\Replacer::addnav('Steps');
		\Replacer::addnav(__('install_common_step','install').' 0 '.__('install_common_title_step_0','install'),BASE_URL);
		$this->s_step_title.= __('install_common_title_step_0','install');
	}
}