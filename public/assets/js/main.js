/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
'use strict';

require([
        'page/ChangeAvatarPage'
    ],
    function (ChangeAvatarPage) {
        var page = new ChangeAvatarPage();
        page.init();
    }
);
