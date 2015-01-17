<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Application;

use EasyForms\Bridges\Twig\FormExtension;
use EasyForms\Bridges\Twig\FormRenderer;
use Slim\Slim;

class Router
{
    public function register(Slim $app)
    {
        $app->get('/', function () use ($app) {

            echo $app->twig->render('index.html.twig');
        });

        $app->get('/theme/:layoutName', function ($layoutName) use ($app) {

            if (!in_array($layoutName, ['default', 'bootstrap3', 'form'])) {
                $app->notFound();
            }

            $app->twig->addExtension(new FormExtension(new FormRenderer($app->twig, "layouts/$layoutName.html.twig")));

            echo $app->twig->render('examples/layout.html.twig', [
                'twitter' => $app->tweetForm->buildView(),
                'layoutName' => $layoutName,
            ]);
        });
    }
}
