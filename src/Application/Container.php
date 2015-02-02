<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Application;

use Application\Actions\ShowLayoutAction;
use EasyForms\Bridges\SymfonyCsrf\CsrfTokenProvider;
use EasyForms\Bridges\Zend\InputFilter\InputFilterValidator;
use ExampleForms\ProductForm;
use ExampleForms\AddToCartForm;
use ExampleForms\Configuration\AddToCartConfiguration;
use ExampleForms\Filters\AddToCartFilter;
use ExampleForms\Filters\CommentFilter;
use ExampleForms\Filters\LoginFilter;
use ExampleForms\Filters\SignUpFilter;
use ExampleForms\LoginForm;
use ExampleForms\SignUpForm;
use ExampleForms\TweetForm;
use ProductCatalog\Catalog;
use ProductCatalog\CatalogSeeder;
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
    /** @var array */
    protected $options;

    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * @param Slim $app
     */
    public function configure(Slim $app)
    {
        $this->registerTwig($app);
        $this->registerForms($app);
        $this->registerValidators($app);
        $this->registerControllers($app);
        $this->registerCaptchas($app);
        $this->registerCsrfTokenProvider($app);
        $this->registerFormsConfiguration($app);
    }

    /**
     * @param Slim $app
     */
    protected function registerControllers(Slim $app)
    {
        $app->showLayoutAction = $app->container->protect(function () use ($app) {
            call_user_func_array(new ShowLayoutAction($app->twig, $app->tweetForm), func_get_args());
        });
    }

    /**
     * @param Slim $app
     */
    protected function registerForms(Slim $app)
    {
        $app->container->singleton('tweetForm', function () {
            return new TweetForm();
        });
        $app->container->singleton('addProductForm', function () {
            return new ProductForm();
        });
        $app->container->singleton('signUpForm', function () {
            return new SignUpForm();
        });
        $app->container->singleton('loginForm', function () use ($app) {
            return new LoginForm($app->tokenProvider);
        });
        $app->container->singleton('productForm', function () use ($app) {
            return new ProductForm();
        });
        $app->container->singleton('addToCartForm', function () {
            return new AddToCartForm();
        });
    }

    /**
     * @param Slim $app
     */
    protected function registerValidators(Slim $app)
    {
        $app->container->singleton('commentValidator', function () use ($app) {
            return new InputFilterValidator($app->commentFilter);
        });
        $app->container->singleton('loginValidator', function () use ($app) {
            return new InputFilterValidator(new LoginFilter($app->tokenProvider));
        });
        $app->container->singleton('signUpValidator', function () {
            return new InputFilterValidator(new SignUpFilter(realpath('uploads')));
        });
        $app->container->singleton('addToCartValidator', function () use ($app) {
            return new InputFilterValidator($app->addToCartFilter);
        });

        $app->container->singleton('commentFilter', function () {
            return new CommentFilter();
        });
        $app->container->singleton('addToCartFilter', function () {
            return new AddToCartFilter();
        });
    }

    /**
     * @param Slim $app
     */
    protected function registerFormsConfiguration(Slim $app)
    {
        $app->container->singleton('addToCartConfiguration', function () use ($app) {
            return new AddToCartConfiguration($app->catalog);
        });
        $app->container->singleton('catalog', function () {
            $catalog = new Catalog();
            $seeder = new CatalogSeeder(require $this->options['products']);
            $seeder->seed($catalog);

            return $catalog;
        });
    }

    /**
     * @param Slim $app
     */
    protected function registerCaptchas(Slim $app)
    {
        $app->container->singleton('imageCaptcha', function () {
            return new Image($this->options['captcha']['image_options']);
        });
        $app->container->singleton('reCaptcha', function () use ($app) {
            return new ReCaptcha([
                'service' => new ReCaptchaService(
                    $this->options['captcha']['recaptcha_public_key'],
                    $this->options['captcha']['recaptcha_private_key'],
                    $params = null,
                    $options = null,
                    $ip = null,
                    new Client($uri = null, ['adapter' => new Client\Adapter\Curl()])
                )
            ]);
        });
    }

    /**
     * @param Slim $app
     */
    protected function registerCsrfTokenProvider(Slim $app)
    {
        $app->container->singleton('tokenProvider', function () use ($app) {
            return new CsrfTokenProvider(
                new CsrfTokenManager(new UriSafeTokenGenerator(), new NativeSessionTokenStorage())
            );
        });
    }

    /**
     * @param Slim $app
     */
    protected function registerTwig(Slim $app)
    {
        $app->container->singleton('loader', function () {
            return new Loader($this->options['twig']['loader_paths']);
        });
        $app->container->singleton('twig', function () use ($app) {
            return new Environment($app->loader, $this->options['twig']['options']);
        });
    }
}
