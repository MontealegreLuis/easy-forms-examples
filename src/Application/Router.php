<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Application;

use Slim\Slim;

class Router
{
    /**
     * @param Slim $app
     */
    public function register(Slim $app)
    {
        $app->get('/', $app->container->get('indexAction'));
        $app->get('/theme/:layoutName', $app->container->get('showLayoutAction'));
        $app->get('/sign-up', $app->container->get('showElementTypes'));
        $app->map('/validation', $app->container->get('showFormValidation'))->via('GET', 'POST');
        $app->map('/captcha/:type', $app->container->get('showCaptchasAction'))->via('GET', 'POST');
        $app->map('/csrf', $app->container->get('showCsrfTokensAction'))->via('GET', 'POST');
        $app->map('/database', $app->container->get('formConfigurationAction'))->via('GET', 'POST');
        $app->map('/edit-information', $app->container->get('editRecordAction'))->via('GET', 'POST');
    }
}
