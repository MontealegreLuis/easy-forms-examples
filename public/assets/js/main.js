/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
'use strict';

require([
        'flight/lib/compose',
        'flight/lib/registry',
        'flight/lib/advice',
        'pages/ChangeAvatarPage'
    ],
    function (compose, registry, advice, ChangeAvatarPage) {
        var page = new ChangeAvatarPage();

        compose.mixin(registry, [advice.withAdvice]);

        page.init();
    }
);
