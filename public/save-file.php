<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright  Mandrágora Web-Based Systems 2014 (http://www.mandragora-web-systems.com)
 */
require __DIR__ . '/../vendor/autoload.php';

use Example\Forms\SignUpForm;
use Example\Forms\Filters\SignUpFilter;
use Forms\Bridges\Twig\FormExtension;
use Forms\Bridges\Twig\FormRenderer;
use Forms\Bridges\Zend\Captcha\ImageCaptchaAdapter;
use Forms\Bridges\Zend\InputFilter\InputFilterValidator;
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
