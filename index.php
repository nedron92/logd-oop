<?php
/**
 * @file    index.php
 * @author  Daniel Becker   <becker_leinad@hotmail.com>
 * @date    23.02.2015
 *
 * @description
 * additional index file for redirecting to the public/index.php file
 */

/*
$language = 'de';
$language_addition = 'DE';
$language_charset = 'UTF-8';

$domain = $language.'_'.$language_addition.'.'.$language_charset;
putenv('LC_ALL='.$language);
setlocale(LC_ALL, $domain);

// Angeben des Pfads der Übersetzungstabellen
bindtextdomain($language, './game_core/i18n/');
bind_textdomain_codeset($language, 'UTF-8');

// Domain auswählen
textdomain($language);

// Ausgeben des Test-Textes
echo _("Willkommen in meiner PHP-Applikation").'<br>';
echo _("Einen schönen Tag noch");
die;
*/

//redirect to the public directory if we needed it.
header('Location: public/',301);
exit (1);