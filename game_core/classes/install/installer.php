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

	/**
	 * @param null|int $i_step
	 */
	public function __construct($i_step=null)
	{
		if(!is_null($i_step)) {
			$this->i_step = $i_step;
		}

		$this->s_step_title = __('install_common_title','install');

		\Replacer::set_hidden_fields(array('character','online','more'));

		switch($this->i_step)
		{
			case 0:
			{
				$this->init();
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
		\Replacer::addnav('Steps');
		\Replacer::addnav(__('install_common_step','install').' 0 '.__('install_common_title_step_0','install'),BASE_URL);
		$this->s_step_title.= __('install_common_title_step_0','install');
	}
}