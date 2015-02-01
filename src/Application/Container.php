<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Application;

use EasyForms\Bridges\SymfonyCsrf\CsrfTokenProvider;
use EasyForms\Bridges\Zend\InputFilter\InputFilterValidator;
use ExampleForms\AddProductForm;
use ExampleForms\Filters\CommentFilter;
use ExampleForms\Filters\SignUpFilter;
use ExampleForms\LoginForm;
use ExampleForms\SignUpForm;
use ExampleForms\TweetForm;
use Slim\Slim;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Security\Csrf\TokenGenerator\UriSafeTokenGenerator;
use Symfony\Component\Security\Csrf\TokenStorage\NativeSessionTokenStorage;
use Twig_Environment as Environment;
use Twig_Loader_Filesystem as Loader;
use Zend\Captcha\Image;
use Zend\Captcha\ReCaptcha;
use Zend\Http\Client;
use ZendService\ReCaptcha\ReCaptcha as ReCaptchaService;

class Container
{
    public function configure(Slim $app)
    {
        $app->container->singleton('loader', function () {
            return new Loader([
                'vendor/comphppuebla/easy-forms/src/EasyForms/Bridges/Twig',
                'app/templates',
            ]);
        });

        $app->container->singleton('twig', function () use ($app) {
            return new Environment($app->loader, [
                'cache' => 'var/cache/twig',
                'debug' => true,
                'strict_variables' => true,
            ]);
        });

        $app->container->singleton('tweetForm', function () {
            return new TweetForm();
        });

        $app->container->singleton('addProductForm', function () {
            return new AddProductForm();
        });

        $app->container->singleton('signUpForm', function () {
            return new SignUpForm();
        });

        $app->container->singleton('loginForm', function () use ($app) {
            return new LoginForm($app->tokenProvider);
        });

        $app->container->singleton('imageCaptcha', function () {
            return new Image([
                'font' => 'fonts/Monaco.ttf',
                'imgDir' => realpath('public/images/captcha'),
                'imgUrl' => '/images/captcha',
            ]);
        });

        $app->container->singleton('reCaptcha', function () use ($app) {
            return new ReCaptcha([
                'service' => new ReCaptchaService(
                    '6Ldawu0SAAAAAIteRKIEA8LaDmMcgrEtDESTEvMo',
                    '6Ldawu0SAAAAAOMHpocI7rAUK1M4yJd5RzX4h2WH',
                    null, // params
                    null, // options
                    null, // IP
                    new Client(null /* URI */, ['adapter' => new Client\Adapter\Curl()])
                )
            ]);
        });

        $app->container->singleton('commentValidator', function () use ($app) {
            return new InputFilterValidator($app->commentFilter);
        });

        $app->container->singleton('commentFilter', function () {
            return new CommentFilter();
        });

        $app->container->singleton('signUpValidator', function () {
            return new InputFilterValidator(new SignUpFilter(realpath('uploads')));
        });

        $app->container->singleton('tokenProvider', function () use ($app) {

            return new CsrfTokenProvider(
                new CsrfTokenManager(new UriSafeTokenGenerator(), new NativeSessionTokenStorage())
            );
        });
    }
}
