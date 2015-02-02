<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Application;

use EasyForms\Bridges\Twig\BlockOptions;
use EasyForms\Bridges\Twig\FormExtension;
use EasyForms\Bridges\Twig\FormRenderer;
use EasyForms\Bridges\Twig\FormTheme;
use EasyForms\Bridges\Zend\Captcha\ImageCaptchaAdapter;
use EasyForms\Bridges\Zend\Captcha\ReCaptchaAdapter;
use ExampleForms\CommentForm;
use Slim\Slim;

class Router
{
    /**
     * @param Slim $app
     */
    public function register(Slim $app)
    {
        $app->get('/', function () use ($app) {

            echo $app->twig->render('index.html.twig');
        });

        $app->get('/theme/:layoutName', $app->showLayoutAction);

        $app->get('/sign-up', $app->showElementTypes);

        $app->map('/validation', $app->showFormValidation)->via('GET', 'POST');

        $app->map('/captcha/:type', $app->showCaptchasAction)->via('GET', 'POST');

        $app->map('/csrf', $app->showCsrfTokensAction)->via('GET', 'POST');

        $app->map('/database', $app->formConfigurationAction)->via('GET', 'POST');

        $app->map('/edit-information', $app->editRecordAction)->via('GET', 'POST');
    }
}
