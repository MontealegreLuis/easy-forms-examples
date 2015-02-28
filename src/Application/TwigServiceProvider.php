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
    /** @var array */
    protected $parameters;

    /**
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @param Slim $app
     */
    public function configure(Slim $app)
    {
        $app->container->singleton('loader', function () {
            return new Loader($this->parameters['twig']['loader_paths']);
        });
        $app->container->singleton('twig', function () use ($app) {
            return new Environment($app->container->get('loader'), $this->parameters['twig']['options']);
        });
    }

}
