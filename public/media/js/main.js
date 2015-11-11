/**
 * @file    main.js
 * @author  Daniel Becker  <becker_leinad@hotmail.com>
 * @date    18.07.2015
 * @package js

 * @description
 * the main javascript file for all templates, based on jQuery
 *
 */

jQuery(document).ready(function() {
//jQuery("#js-base-url").attr('href') + "/ajax/ajaxhandler.php/is_session_expired",
    jQuery.ajax({
        type: "POST",
        dataType: "json",
        url: jQuery("#js-base-url").attr('href') + "ajax/is_session_expired",
        success: function(data) {
            alert("Test");
        }
    });

});