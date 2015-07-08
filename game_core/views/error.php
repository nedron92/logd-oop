<?php defined('CORE_PATH') or die('No direct script access.');
/**
 * @file    error.php
 * @author  Daniel Becker   <becker_leinad@hotmail.com>
 * @date    27.02.2015
 * @package game_core
 * @subpackage views
 *
 * @description
 * The error-view of the game
 *
 */

if(strpos(ERROR_CODE,'TEMPLATE') !== false) {
	echo ERROR_MESSAGE;
	echo '<br><br><b>Code: '.ERROR_CODE.'</b>';
}else{
	Replacer::output(ERROR_MESSAGE);
	Replacer::output('<br><b>Code: '.ERROR_CODE.'</b>');
}
