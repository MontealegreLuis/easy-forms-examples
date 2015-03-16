/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
define([
    'jquery',
    'components/UiFileUploadProgress'
], function ($, UiFileUploadProgress) {
    'use strict';

    var ChangeAvatarPage = function() {
        /**
         * Module's initialization method
         */
        this.init = function() {
            UiFileUploadProgress.attachTo('#avatar');
        }
    };

    /**
     * Module exports
     */
    return ChangeAvatarPage;
});
