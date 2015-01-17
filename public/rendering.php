<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
require __DIR__ . '/../vendor/autoload.php';

use ExampleForms\LoginForm;
use ExampleForms\SignUpForm;
use ExampleForms\Filters\LoginFilter;
use EasyForms\Bridges\SymfonyCsrf\CsrfTokenProvider;
use EasyForms\Bridges\Twig\FormExtension;
use EasyForms\Bridges\Twig\FormRenderer;
use EasyForms\Bridges\Zend\Captcha\ImageCaptchaAdapter;
use EasyForms\Bridges\Zend\InputFilter\InputFilterValidator;
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
