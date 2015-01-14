<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
require __DIR__ . '/../vendor/autoload.php';

use ExampleForms\LoginForm;
use ExampleForms\Filters\LoginFilter;
use Forms\Bridges\SymfonyCsrf\CsrfTokenProvider;
use Forms\Bridges\Twig\FormExtension;
use Forms\Bridges\Twig\FormRenderer;
use Forms\Bridges\Zend\InputFilter\InputFilterValidator;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Security\Csrf\TokenGenerator\UriSafeTokenGenerator;
use Symfony\Component\Security\Csrf\TokenStorage\NativeSessionTokenStorage;

$tokenProvider = new CsrfTokenProvider(
    new CsrfTokenManager(new UriSafeTokenGenerator(), new NativeSessionTokenStorage())
);

$form = new LoginForm($tokenProvider);
$form->submit($_POST);

$validator = new InputFilterValidator(new LoginFilter($tokenProvider));
$isValid = $validator->validate($form);

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

echo $environment->render('validation.html.twig', [
    'login' => $form->buildView(),
]);
