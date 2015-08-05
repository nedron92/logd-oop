<?php defined('CORE_PATH') or die('No direct script access.');
/**
 * @file    install.php
 * @author  Daniel   <becker_leinad@hotmail.com>
 * @date    04.08.2015
 * @package game_core
 * @subpackage views
 *
 * @description
 * The view for the install-script of the game
 *
 * @var string  $i_step     the actual step of installation
 */
$a_supported_languages = I18N::get_all_supported_languages(false);
$s_html = '<form method="post" action="'.BASE_URL.'"> ';
$s_html.= '<select name="game_language">';
foreach($a_supported_languages as $s_language_code => $s_language_name)
{
	$s_is_selected = null;
	if(I18N::get_language() === $s_language_code) {
		$s_is_selected = 'selected';
	}
	$s_html.= '<option '.$s_is_selected.' value="'.$s_language_code.'">'.$s_language_name.'</option>';
}

$s_html.= '</select> <input type="submit"> </form>';

Replacer::output($s_html);
