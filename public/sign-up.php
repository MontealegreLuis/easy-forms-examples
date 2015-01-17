<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
require __DIR__ . '/../vendor/autoload.php';

use ExampleForms\SignUpForm;
use EasyForms\Bridges\Twig\FormExtension;
use EasyForms\Bridges\Twig\FormRenderer;
use EasyForms\Bridges\Zend\Captcha\ImageCaptchaAdapter;
use Zend\Captcha\Image;

$signUpForm = new SignUpForm(new ImageCaptchaAdapter(new Image([
    'font' => __DIR__ . '/../fonts/Monaco.ttf',
    'imgDir' => __DIR__ . '/images/captcha',
    'imgUrl' => 'images/captcha',
])));


$loader = new Twig_Loader_Filesystem([
    __DIR__ . '/../vendor/comphppuebla/simple-form/src/Forms/Bridges/Twig',
    __DIR__ . '/../app/templates',
]);
$environment = new Twig_Environment($loader, [
    'cache' => __DIR__ . '/../var/cache/twig',
    'debug' => true,
    'strict_variables' => true,
]);
$environment->addExtension(new FormExtension(new FormRenderer($environment, 'forms/form.html.twig')));

echo $environment->render('captcha.html.twig', [
    'signUp' => $signUpForm->buildView(),
]);
