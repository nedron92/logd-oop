/**
 * @file    game.js
 * @author  Daniel Becker  <becker_leinad@hotmail.com>
 * @date    07.03.2015
 * @package js
 *
 * @description
 * the main javascript file, based on jQuery
 *
 */

jQuery(document).ready(function(){

    jQuery('.game .navigation, .game .content').syncHeight({ 'updateOnResize': true});

    jQuery('a').hover(function(){
        jQuery('.game .navigation, .game .content').syncHeight({ 'updateOnResize': true});
    });

});