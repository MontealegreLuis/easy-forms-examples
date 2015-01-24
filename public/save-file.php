<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
require __DIR__ . '/../vendor/autoload.php';

use ExampleForms\SignUpForm;
use ExampleForms\Filters\SignUpFilter;
use EasyForms\Bridges\Twig\BlockOptions;
use EasyForms\Bridges\Twig\FormExtension;
use EasyForms\Bridges\Twig\FormRenderer;
use EasyForms\Bridges\Twig\FormTheme;
use EasyForms\Bridges\Zend\Captcha\ImageCaptchaAdapter;
use EasyForms\Bridges\Zend\InputFilter\InputFilterValidator;
use Zend\Captcha\Image;

$captcha = new Image([
    'font' => __DIR__ . '/../fonts/Monaco.ttf',
    'imgDir' => __DIR__ . '/images/captcha',
    'imgUrl' => 'images/captcha',
]);

$signUpForm = new SignUpForm(new ImageCaptchaAdapter($captcha));
$signUpForm->submit(array_merge($_POST, $_FILES));

$validator = new InputFilterValidator(new SignUpFilter($captcha));
$isValid = $validator->validate($signUpForm);

$loader = new Twig_Loader_Filesystem([
    __DIR__ . '/../vendor/comphppuebla/easy-forms/src/EasyForms/Bridges/Twig',
    __DIR__ . '/../app/templates',
]);
$environment = new Twig_Environment($loader, [
    'cache' => __DIR__ . '/../var/cache/twig',
    'debug' => true,
    'strict_variables' => true,
]);
$theme = new FormTheme($environment, 'layouts/form.html.twig');
$environment->addExtension(new FormExtension(new FormRenderer($theme, new BlockOptions())));

echo $environment->render('captcha.html.twig', [
    'signUp' => $signUpForm->buildView(),
]);
