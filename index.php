<?php
/**
 * @file    index.php
 * @author  Daniel Becker   <becker_leinad@hotmail.com>
 * @date    23.02.2015
 *
 * @description
 * additional index file for redirecting to the public/index.php file
 */

//redirect to the public directory if we needed it.
header('Location: public/',301);
exit (1);