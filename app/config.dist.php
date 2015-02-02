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
    'products' => 'app/products.php',
];
