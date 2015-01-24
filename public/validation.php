<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
require __DIR__ . '/../vendor/autoload.php';

use ExampleForms\LoginForm;
use ExampleForms\Filters\LoginFilter;
use EasyForms\Bridges\SymfonyCsrf\CsrfTokenProvider;
use EasyForms\Bridges\Twig\BlockOptions;
use EasyForms\Bridges\Twig\FormExtension;
use EasyForms\Bridges\Twig\FormRenderer;
use EasyForms\Bridges\Twig\FormTheme;
use EasyForms\Bridges\Zend\InputFilter\InputFilterValidator;
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

echo $environment->render('validation.html.twig', [
    'login' => $form->buildView(),
]);
