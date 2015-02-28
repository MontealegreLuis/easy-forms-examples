<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Application;

use ComPHPPuebla\Slim\ServiceProvider;
use Slim\Slim;
use Twig_Loader_Filesystem as Loader;
use Twig_Environment as Environment;

class TwigServiceProvider implements ServiceProvider
{
    /**
     * @param Slim $app
     * @param array $parameters
     * @return void
     */
    public function configure(Slim $app, array $parameters = [])
    {
        $app->container->singleton('loader', function () use ($parameters) {
            return new Loader($parameters['twig']['loader_paths']);
        });
        $app->container->singleton('twig', function () use ($app, $parameters) {
            return new Environment($app->container->get('loader'), $parameters['twig']['options']);
        });
    }

}
