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

    var data = { };

    jQuery.ajax({
        type: "POST",
        dataType: "json",
        data: data,
        url: jQuery("#js-base-url").attr('href') + "ajax/is_session_expired",
        success: function(data) {
            console.log(data);
        },

        error: function(msg) {
            console.log(msg);
        }

    });

});