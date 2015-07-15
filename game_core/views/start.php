<?php defined('CORE_PATH') or die('No direct script access.');
/**
 * @file    start.php
 * @author  Daniel Becker   <becker_leinad@hotmail.com>
 * @date    27.02.2015
 * @package game_core
 * @subpackage views
 *
 * @description
 * The first start-view file of the game
 *
 */

Replacer::addnav('Titel 1');
Replacer::addnav('Link 1',BASE_URL);
Replacer::addnav('Titel 2');

Replacer::output('Testcontent 1');
Replacer::output('Testcontent 2');

Replacer::addcharstat('Vital Info');
Replacer::addcharstat('Linke Seite 1','Rechte Seite 1');
Replacer::addcharstat('Status');
Replacer::addcharstat('Linke Seite 2','Rechte Seite 2');
