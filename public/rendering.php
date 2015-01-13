<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright  Mandrágora Web-Based Systems 2014 (http://www.mandragora-web-systems.com)
 */
require __DIR__ . '/../vendor/autoload.php';

use Example\Forms\LoginForm;
use Example\Forms\SignUpForm;
use Example\Forms\Filters\LoginFilter;
use Forms\Bridges\SymfonyCsrf\CsrfTokenProvider;
use Forms\Bridges\Twig\FormExtension;
use Forms\Bridges\Twig\FormRenderer;
use Forms\Bridges\Zend\Captcha\ImageCaptchaAdapter;
use Forms\Bridges\Zend\InputFilter\InputFilterValidator;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Security\Csrf\TokenGenerator\UriSafeTokenGenerator;
use Symfony\Component\Security\Csrf\TokenStorage\NativeSessionTokenStorage;
use Zend\Captcha\Image;

$tokenProvider = new CsrfTokenProvider(
    new CsrfTokenManager(new UriSafeTokenGenerator(), new NativeSessionTokenStorage())
);

$signUpForm = new SignUpForm(new ImageCaptchaAdapter(new Image([
    'font' => __DIR__ . '/../fonts/Monaco.ttf',
    'imgDir' => __DIR__ . '/images/captcha',
    'imgUrl' => 'images/captcha',
])));

$loginForm = new LoginForm($tokenProvider);
$loginForm->submit([
    'username' => 'john.doe',
    'password' => 'pwd',
    'remember_me' => 'remember_me',
]);
$loginFormValidator = new InputFilterValidator(new LoginFilter($tokenProvider));

$isValid = $loginFormValidator->validate($loginForm);

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

echo $environment->render('index.html.twig', [
    'login' => $loginForm->buildView(),
    'signUp' => $signUpForm->buildView(),
]);
