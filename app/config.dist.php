<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
return [
    'captcha' => [
        'recaptcha_private_key' => '##recaptcha.private_key##',
        'recaptcha_public_key' => '##recaptcha.public_key##',
        'image_options' => [
            'font' => 'fonts/Monaco.ttf',
            'imgDir' => realpath('public/images/captcha'),
            'imgUrl' => '/images/captcha',
        ],
    ],
    'twig' => [
        'loader_paths' => [
            'vendor/comphppuebla/easy-forms/src/EasyForms/Bridges/Twig',
            'app/templates',
        ],
        'options' => [
            'cache' => 'var/cache/twig',
            'debug' => true,
            'strict_variables' => true,
        ]
    ],
    'products' => [
        [
            'productId' => 1,
            'unitPrice' => 100,
            'name' => 'Super Mario Bros',
            'description' => 'It is a 1985 platform video game internally developed by Nintendo and published by Nintendo as a pseudo-sequel to the 1983 game Mario Bros.',
            'price' => [
                'cost_price' => ['amount' => 20000, 'currency' => 'MXN'],
                'sale_price' => ['amount' => 1000, 'currency' => 'USD'],
            ]
        ],
        ['productId' => 2, 'unitPrice' => 120, 'name' => 'Call of Duty'],
        ['productId' => 3, 'unitPrice' => 110, 'name' => 'Mortal Kombat II'],
    ],
];
