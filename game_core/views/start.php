<?php defined('CORE_PATH') or die('No direct script access.');
/**
 * Created by PhpStorm.
 * User: Daniel
 * Date: 27.02.2015
 * Time: 07:54
 */



Replacer::addnav('Titel 1');
Replacer::addnav('Link 1','logd/public');
Replacer::addnav('Titel 2');

Replacer::output('Testcontent 1');
Replacer::output('Testcontent 2');

Replacer::addcharstat('Vital Info');
Replacer::addcharstat('Linke Seite 1','Rechte Seite 1');
Replacer::addcharstat('Status');
Replacer::addcharstat('Linke Seite 2','Rechte Seite 2');
