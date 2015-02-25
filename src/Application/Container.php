<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Application;

use Application\Actions\CompositeElementAction;
use Application\Actions\EditRecordAction;
use Application\Actions\FormConfigurationAction;
use Application\Actions\FormValidationAction;
use Application\Actions\IndexAction;
use Application\Actions\ShowCaptchaAction;
use Application\Actions\ShowCsrfTokensAction;
use Application\Actions\ShowElementTypesAction;
use Application\Actions\ShowLayoutAction;
use EasyForms\Bridges\Symfony\Security\CsrfTokenProvider;
use EasyForms\Bridges\Zend\InputFilter\InputFilterValidator;
use Example\Configuration\AddToCartConfiguration;
use Example\Configuration\ProductPricingConfiguration;
use Example\Filters\AddToCartFilter;
use Example\Filters\CommentFilter;
use Example\Filters\LoginFilter;
use Example\Filters\ProductPricingFilter;
use Example\Filters\SignUpFilter;
use Example\Forms\AddToCartForm;
use Example\Forms\LoginForm;
use Example\Forms\ProductForm;
use Example\Forms\ProductPricingForm;
use Example\Forms\SignUpForm;
use Example\Forms\TweetForm;
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
        $this->registerServices($app);
        $this->registerControllers($app);
    }

    /**
     * @param Slim $app
     */
    protected function registerControllers(Slim $app)
    {
        $app->container->set('indexAction', $app->container->protect(function () use ($app) {
            call_user_func(new IndexAction($app->container->get('twig')));
        }));
        $app->container->set('showLayoutAction', $app->container->protect(function () use ($app) {
            call_user_func_array(
                new ShowLayoutAction(
                    $app->container->get('twig'),
                    new TweetForm(),
                    $app->container->get('productForm')
                ),
                func_get_args()
            );
        }));
        $app->container->set('showElementTypes', $app->container->protect(function () use ($app) {
            call_user_func(new ShowElementTypesAction(
                $app->container->get('twig'),
                $app->container->get('signUpForm')
            ));
        }));
        $app->container->set('showFormValidation', $app->container->protect(function () use ($app) {
            call_user_func_array(
                new FormValidationAction(
                    $app->container->get('twig'),
                    $app->container->get('signUpForm'),
                    new InputFilterValidator(new SignUpFilter(realpath('uploads')))
                ), [$app->request]
            );
        }));
        $app->container->set('showCaptchaAction', $app->container->protect(function () use ($app) {
            call_user_func_array(new ShowCaptchaAction(
                $app->container->get('twig'),
                new Image($this->options['captcha']['image_options']),
                new ReCaptcha([
                    'service' => new ReCaptchaService(
                        $this->options['captcha']['recaptcha_public_key'],
                        $this->options['captcha']['recaptcha_private_key'],
                        $params = null,
                        $options = null,
                        $ip = null,
                        new Client($uri = null, ['adapter' => new Client\Adapter\Curl()])
                    )
                ]),
                $app->container->get('commentFilter'),
                new InputFilterValidator($app->container->get('commentFilter'))
            ), array_merge([$app->request], func_get_args()));
        }));
        $app->container->set('showCsrfTokensAction', $app->container->protect(function () use ($app) {
            call_user_func_array(new ShowCsrfTokensAction(
                $app->container->get('twig'),
                new LoginForm($app->container->get('tokenProvider')),
                new InputFilterValidator(new LoginFilter($app->container->get('tokenProvider')))
            ), [$app->request]);
        }));
        $app->container->set('formConfigurationAction', $app->container->protect(function () use ($app) {
            call_user_func_array(new FormConfigurationAction(
                $app->container->get('twig'),
                new AddToCartForm(),
                $app->container->get('addToCartFilter'),
                new AddToCartConfiguration($app->container->get('catalog')),
                new InputFilterValidator($app->container->get('addToCartFilter'))
            ), [$app->request]);
        }));
        $app->container->set('editRecordAction', $app->container->protect(function () use ($app) {
            call_user_func(new EditRecordAction(
                $app->container->get('twig'),
                $app->container->get('productForm'),
                $app->container->get('catalog')
            ));
        }));
        $app->container->set('compositeElementAction', $app->container->protect(function () use ($app) {
            call_user_func_array(new CompositeElementAction(
                $app->container->get('twig'),
                $app->container->get('pricingForm'),
                new InputFilterValidator($app->container->get('pricingFilter'))
            ), [$app->request]);
        }));
    }

    /**
     * @param Slim $app
     */
    protected function registerServices(Slim $app)
    {
        $app->container->singleton('productForm', function () {
            return new ProductForm();
        });
        $app->container->singleton('signUpForm', function () {
            return new SignUpForm();
        });
        $app->container->singleton('productForm', function () use ($app) {
            return new ProductForm();
        });
        $app->container->singleton('addToCartFilter', function () {
            return new AddToCartFilter();
        });
        $app->container->singleton('commentFilter', function () {
            return new CommentFilter();
        });
        $app->container->singleton('catalog', function () {
            $catalog = new Catalog();
            $seeder = new CatalogSeeder($this->options['products']);
            $seeder->seed($catalog);

            return $catalog;
        });
        $app->container->singleton('tokenProvider', function () use ($app) {
            return new CsrfTokenProvider(
                new CsrfTokenManager(new UriSafeTokenGenerator(), new NativeSessionTokenStorage())
            );
        });
        $app->container->singleton('loader', function () {
            return new Loader($this->options['twig']['loader_paths']);
        });
        $app->container->singleton('twig', function () use ($app) {
            return new Environment($app->container->get('loader'), $this->options['twig']['options']);
        });
        $app->container->singleton('pricingConfiguration', function () use ($app) {
            return new ProductPricingConfiguration($app->container->get('catalog'));
        });
        $app->container->singleton('pricingForm', function () use ($app) {
            $form = new ProductPricingForm();
            $form->configure($app->container->get('pricingConfiguration'));

            return $form;
        });
        $app->container->singleton('pricingFilter', function () use ($app) {
            $filter = new ProductPricingFilter();
            $filter->configure($app->container->get('pricingConfiguration'));

            return $filter;
        });
    }
}
