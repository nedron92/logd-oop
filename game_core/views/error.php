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
 * @var string  $s_error_message    the given error message
 * @var string  $s_error_code       the given error code
 * @var string  $s_error_file       the given error file
 * @var string  $s_error_line       the given error line
 */

if(strpos($s_error_code,'TEMPLATE') !== false) {
	echo $s_error_message;
	echo '<br><br><b>Code: '.$s_error_code.'</b>';
}else{
	Replacer::output($s_error_message);
	Replacer::output('<br><b>Code:  '.$s_error_code .'</b>');
	Replacer::output('<br><b>File:  '.$s_error_file .'</b>');
	Replacer::output('<br><b>Line:  '.$s_error_line .'</b>');

}
