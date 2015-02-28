<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright  Mandrágora Web-Based Systems 2015 (http://www.mandragora-web-systems.com)
 */
namespace ProductCatalog;

use ComPHPPuebla\Slim\ServiceProvider;
use ProductCatalog\Catalog\Catalog;
use ProductCatalog\Catalog\CatalogSeeder;
use Slim\Slim;

class ProductCatalogServices implements ServiceProvider
{
    /**
     * @param Slim $app
     * @param array $parameters
     */
    public function configure(Slim $app, array $parameters = [])
    {
        $app->container->singleton('catalog', function () use ($parameters) {
            $catalog = new Catalog();
            $seeder = new CatalogSeeder($parameters['products']);
            $seeder->seed($catalog);

            return $catalog;
        });
    }
}
