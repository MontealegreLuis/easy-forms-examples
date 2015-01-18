<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Application;

use EasyForms\Bridges\SymfonyCsrf\CsrfTokenProvider;
use ExampleForms\AddProductForm;
use ExampleForms\LoginForm;
use ExampleForms\TweetForm;
use Slim\Slim;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Security\Csrf\TokenGenerator\UriSafeTokenGenerator;
use Symfony\Component\Security\Csrf\TokenStorage\NativeSessionTokenStorage;
use Twig_Environment as Environment;
use Twig_Loader_Filesystem as Loader;

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

        $app->container->singleton('tweetForm', function () use ($app) {
            return new TweetForm();
        });

        $app->container->singleton('addProductForm', function () use ($app) {
            return new AddProductForm();
        });

        $app->container->singleton('loginForm', function () use ($app) {
            return new LoginForm($app->tokenProvider);
        });

        $app->container->singleton('tokenProvider', function () use ($app) {

            return new CsrfTokenProvider(
                new CsrfTokenManager(new UriSafeTokenGenerator(), new NativeSessionTokenStorage())
            );
        });
    }
}
