<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Application;

use ComPHPPuebla\Slim\Controllers;
use Example\ExampleControllers;
use Slim\Slim;

class ApplicationControllers extends Controllers
{
    protected function init()
    {
        $this
            ->add(new ExampleControllers())
        ;
    }
}
