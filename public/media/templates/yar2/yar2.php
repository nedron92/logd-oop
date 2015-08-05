<?php defined('CORE_PATH') or die('No direct script access.');
/**
 * @file    yar2.php
 * @author  Daniel Becker <becker_leinad@hotmail.com>
 * @date    05.03.2015
 * @package templates
 * @subpackage  yar2
 *
 * @description
 * The new yarbrough Theme based on the original LOGD v0.9.7 one.
 * Copyright of the original theme: Chris Yarbrough
 *
 * @var $template_directory
 */
?>

<!DOCTYPE html>
<html lang="{language}">
<head>
    <meta charset="UTF-8">
    <title>{title}</title>
</head>
<body>
<div class="header col-md-12 col-xs-12">
    <div class="title-text col-md-6 col-xs-6">
        <h1>{title}</h1>
    </div>
    <div class="title-image col-md-6 col-xs-6">
        <img align="right" src="<?php echo $template_directory;?>images/title.png" alt="{title-image}"/>
    </div>
</div>

<div class="game col-md-12 col-xs-12">

    <div class="navigation col-md-2 col-xs-2">

        <div class="navigation-menu">
            <div class="nav-top">
                <img src="<?php echo $template_directory;?>images/navtop.png" alt="{navtop}"/>
            </div>
            <div class="nav-content">
                <p>{navigation}</p>
            </div>
            <div class="nav-bottom">
                <img src="<?php echo $template_directory;?>images/navbottom.png" alt="{navbottom}"/>
            </div>
        </div>

        <div class="more {more-hidden}">
          <p class="motd"> <a href="{motd-link}">{motd}</a> </p>
		  <p> <a href="{mail-link}">{mail}</a> </p>
          <p> <a href="{petition-link}">{petition}</a> </p>
          <p> <a href="{forum-link}">{forum}</a> </p>
		  <p> <a href="{chat-link}">{chat}</a> </p>
        </div>

        <div class="character {character-hidden} clearfix">
            <div class="stats-table clearfix">
            {stats}
            <!--!stats-->
                <div class="stats-head col-md-12 col-xs-12">
                    <p>{stats-head}</p>
                </div>

				<div class="stats-content col-md-6 col-xs-6">
					<p>{stats-left}</p>
				</div>

				<div class="stats-content col-md-6 col-xs-6">
					<p>{stats-right}</p>
				</div>
			<!--end!-->
            </div>
        </div>
    </div>

    <div class="content col-md-10 col-xs-10">
        <div class="content-text col-md-10 col-xs-10">
            <div class="content-box">
                <p>{game}</p>
            </div>
        </div>

        <div class="content-right col-md-2 col-xs-2">

            <div class="content-online {online-hidden}">
                <div class="online-top">
                    <img src="<?php echo $template_directory;?>images/navtop.png" alt="{navtop}"/>
                </div>
                <div class="online-content">
                    <p>{onlineuser}</p>
                </div>
                <div class="online-bottom">
                    <img src="<?php echo $template_directory;?>images/navbottom.png" alt="{navbottom}"/>
                </div>
            </div>

        </div>

    </div>

</div>

<div class="footer col-md-12 col-xs-12"></div>
<div class="copyright-source col-md-12 col-xs-12">

    <div class="copyright col-md-6 col-xs-6">
        <p>{copyright}</p>
	    <p>Design: Chris Yarbrough, Reworking: 2015, Daniel Becker</p>
        <p>{pagegen}</p>
        <p>{version}</p>
    </div>

    <div class="source col-md-6 col-xs-6">
        <p>{source}</p>
    </div>

</div>

</body>
</html>
