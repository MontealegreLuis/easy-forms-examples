<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright  Mandrágora Web-Based Systems 2015 (http://www.mandragora-web-systems.com)
 */
namespace Example;

use ComPHPPuebla\Slim\ControllerProvider;
use ComPHPPuebla\Slim\ControllerResolver;
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
        $app->get('/theme/:layoutName', $resolver->resolve($app, 'example.show_layout:switchLayout'));
        $app->get('/sign-up', $resolver->resolve($app, 'example.show_types:showTypes'));
        $app->map('/validation', $resolver->resolve($app, 'example.show_validation:validate'))->via('GET', 'POST');
        $app->map('/captcha/:type', $resolver->resolve($app, 'example.show_captcha:switchCaptcha'))->via('GET', 'POST');
        $app->map('/csrf', $resolver->resolve($app, 'example.show_csrf_token:showCsrfToken'))->via('GET', 'POST');
        $app->map('/database', $resolver->resolve($app, 'example.form_configuration:configureForm'))->via('GET', 'POST');
        $app->map('/edit-information', $resolver->resolve($app, 'example.edit_record:editProduct'))->via('GET', 'POST');
        $app->map('/composite-element', $resolver->resolve($app, 'example.composite_element:showCompositeElement'))->via('GET', 'POST');
    }
}
