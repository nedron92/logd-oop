/**
 * @file    game.js
 * @author  Daniel Becker  <becker_leinad@hotmail.com>
 * @date    07.03.2015
 * @package js
 * @subpackage  templates
 *
 * @description
 * the game javascript file for the template yar2, based on jQuery
 *
 */

jQuery(document).ready(function(){

    jQuery('.game .navigation, .game .content').syncHeight({ 'updateOnResize': true});

    jQuery('a').hover(function(){
        jQuery('.game .navigation, .game .content').syncHeight({ 'updateOnResize': true});
    });

});