<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Application;

use ComPHPPuebla\Slim\Services;
use Example\ExampleServices;
use ProductCatalog\ProductCatalogServices;

class ApplicationServices extends Services
{
    protected function init()
    {
        $this
            ->add(new TwigServiceProvider())
            ->add(new ProductCatalogServices())
            ->add(new ExampleServices())
        ;
    }
}
