<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Example;

use Application\Actions\FormRequest;
use ComPHPPuebla\Slim\ControllerProvider;
use ComPHPPuebla\Slim\ControllerResolver;
use EasyForms\Bridges\Zend\InputFilter\InputFilterValidator;
use Slim\Slim;

class ExampleControllers implements ControllerProvider
{
    /**
     * @param Slim $app
     * @param ControllerResolver $resolver
     */
    public function register(Slim $app, ControllerResolver $resolver)
    {
        $app->get('/', $resolver->resolve($app, 'example.index:showIndex'));
        $app->get('/theme/:layoutName', $resolver->resolve($app, 'example.layout:switchLayout'));
        $app->get('/sign-up', $resolver->resolve($app, 'example.types:showTypes'));
        $app->map('/validation', $resolver->resolve($app, 'example.validation:validate'))->via('GET', 'POST');
        $app->map('/captcha/:type', $resolver->resolve($app, 'example.captcha:switchCaptcha'))->via('GET', 'POST');
        $app->map('/csrf', $resolver->resolve($app, 'example.csrf_token:showCsrfToken'))->via('GET', 'POST');
        $app->map('/database', $resolver->resolve($app, 'example.configuration:configureForm'))->via('GET', 'POST');
        $app->map('/edit-information', $resolver->resolve($app, 'example.edit_record:editProduct'))->via('GET', 'POST');
        $app->get('/composite-element', $resolver->resolve($app, 'example.composite_element:showForm'));
        $app->post('/composite-element', $resolver->resolve(
            $app,
            'example.composite_element:processForm',
            function($arguments) use ($app) {
                return [new FormRequest(
                    $arguments[0], // the request
                    $app->container->get('pricingForm'),
                    new InputFilterValidator($app->container->get('pricingFilter'))
                )];
            }
        ));
        $app->get('/upload-progress', $resolver->resolve($app, 'example.upload_progress:changeAvatar'));
    }
}
