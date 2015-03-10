/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
define([
    /* Third party dependencies */
    'jquery'
], function ($) {
    'use strict';

    var ChangeAvatarPage = function() {
        /**
         * Module's initialization method
         */
        this.init = function() {
            var button = '<button type="button" id="replace" class="btn btn-default"><span class="glyphicon glyphicon-upload"></span> Select your new avatar</button>';
            var text = '<input type="text" class="form-control" disabled id="replace-text">';
            var $avatar = $('#avatar');
            $avatar.addClass('hidden');
            $avatar.after(button);
            $avatar.after(text);
            $('.upload').addClass('form-inline');
            $avatar.on('change', function(){
                $('#replace-text').val($avatar.val());
            });
            $('#replace').on('click', function() {
                $avatar.click();
            });
        }
    };

    /**
     * Module exports
     */
    return ChangeAvatarPage;
});
