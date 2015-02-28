<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright  Mandrágora Web-Based Systems 2015 (http://www.mandragora-web-systems.com)
 */
namespace Example;

use ComPHPPuebla\Slim\ServiceProvider;
use EasyForms\Bridges\Symfony\Security\CsrfTokenProvider;
use EasyForms\Bridges\Zend\InputFilter\InputFilterValidator;
use Example\Actions\CompositeElementAction;
use Example\Actions\EditRecordAction;
use Example\Actions\FormConfigurationAction;
use Example\Actions\FormValidationAction;
use Example\Actions\IndexAction;
use Example\Actions\ShowCaptchaAction;
use Example\Actions\ShowCsrfTokensAction;
use Example\Actions\ShowElementTypesAction;
use Example\Actions\ShowLayoutAction;
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
use Slim\Slim;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Security\Csrf\TokenGenerator\UriSafeTokenGenerator;
use Symfony\Component\Security\Csrf\TokenStorage\NativeSessionTokenStorage;
use Zend\Captcha\Image;
use Zend\Captcha\ReCaptcha;
use Zend\Http\Client;
use ZendService\ReCaptcha\ReCaptcha as ReCaptchaService;

class ExampleServices implements ServiceProvider
{
    /** @var array */
    protected $parameters;

    /**
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    public function configure(Slim $app)
    {
        $app->container->set('example.index', function () use ($app) {
            return new IndexAction($app->container->get('twig'));
        });
        $app->container->set('example.show_layout', function () use ($app) {
            return new ShowLayoutAction(
                $app->container->get('twig'),
                new TweetForm(),
                $app->container->get('productForm')
            );
        });
        $app->container->set('example.show_types', function () use ($app) {
            return new ShowElementTypesAction(
                $app->container->get('twig'),
                $app->container->get('signUpForm')
            );
        });
        $app->container->set('example.show_validation', function () use ($app) {
            return new FormValidationAction(
                $app->container->get('twig'),
                $app->container->get('signUpForm'),
                new InputFilterValidator(new SignUpFilter(realpath('uploads')))
            );
        });
        $app->container->set('example.show_captcha', function () use ($app) {
            return new ShowCaptchaAction(
                $app->container->get('twig'),
                new Image($this->parameters['captcha']['image_options']),
                new ReCaptcha([
                    'service' => new ReCaptchaService(
                        $this->parameters['captcha']['recaptcha_public_key'],
                        $this->parameters['captcha']['recaptcha_private_key'],
                        $params = null,
                        $options = null,
                        $ip = null,
                        new Client($uri = null, ['adapter' => new Client\Adapter\Curl()])
                    )
                ]),
                $app->container->get('commentFilter'),
                new InputFilterValidator($app->container->get('commentFilter'))
            );
        });
        $app->container->set('example.show_csrf_token', function () use ($app) {
            return new ShowCsrfTokensAction(
                $app->container->get('twig'),
                new LoginForm($app->container->get('tokenProvider')),
                new InputFilterValidator(new LoginFilter($app->container->get('tokenProvider')))
            );
        });
        $app->container->set('example.form_configuration', function () use ($app) {
            return new FormConfigurationAction(
                $app->container->get('twig'),
                new AddToCartForm(),
                $app->container->get('addToCartFilter'),
                new AddToCartConfiguration($app->container->get('catalog')),
                new InputFilterValidator($app->container->get('addToCartFilter'))
            );
        });
        $app->container->set('example.edit_record', function () use ($app) {
            return new EditRecordAction(
                $app->container->get('twig'),
                $app->container->get('productForm'),
                $app->container->get('catalog')
            );
        });
        $app->container->set('example.composite_element', function () use ($app) {
            return new CompositeElementAction(
                $app->container->get('twig'),
                $app->container->get('pricingForm'),
                new InputFilterValidator($app->container->get('pricingFilter')),
                $app->container->get('catalog')
            );
        });
        $app->container->singleton('productForm', function () {
            return new ProductForm();
        });
        $app->container->singleton('signUpForm', function () {
            return new SignUpForm();
        });
        $app->container->singleton('addToCartFilter', function () {
            return new AddToCartFilter();
        });
        $app->container->singleton('commentFilter', function () {
            return new CommentFilter();
        });
        $app->container->singleton('tokenProvider', function () use ($app) {
            return new CsrfTokenProvider(
                new CsrfTokenManager(new UriSafeTokenGenerator(), new NativeSessionTokenStorage())
            );
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
