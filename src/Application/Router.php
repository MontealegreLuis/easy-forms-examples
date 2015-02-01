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
    public function register(Slim $app)
    {
        $app->get('/', function () use ($app) {

            echo $app->twig->render('index.html.twig');
        });

        $app->get('/theme/:layoutName', function ($layoutName) use ($app) {

            if (!in_array($layoutName, ['default', 'bootstrap3', 'required', 'optional'])) {
                $app->notFound();
            }

            $renderer = new FormRenderer(
                new FormTheme($app->twig, "layouts/$layoutName.html.twig"), new BlockOptions()
            );
            $app->twig->addExtension(new FormExtension($renderer));

            echo $app->twig->render('examples/layout.html.twig', [
                'twitter' => $app->tweetForm->buildView(),
                'layoutName' => $layoutName,
            ]);
        });

        $app->get('/inline-theme', function () use ($app) {

            $renderer = new FormRenderer(new FormTheme($app->twig, "layouts/required.html.twig"), new BlockOptions());
            $app->twig->addExtension(new FormExtension($renderer));

            echo $app->twig->render('examples/inline-layout.html.twig', [
                'product' => $app->addProductForm->buildView(),
            ]);
        });

        $app->get('/sign-up', function () use ($app) {

            $renderer = new FormRenderer(new FormTheme($app->twig, "layouts/optional.html.twig"), new BlockOptions());
            $app->twig->addExtension(new FormExtension($renderer));

            echo $app->twig->render('examples/form-elements.html.twig', [
                'signUp' => $app->signUpForm->buildView(),
            ]);
        });

        $app->map('/validation', function () use ($app) {

            $renderer = new FormRenderer(new FormTheme($app->twig, "layouts/optional.html.twig"), new BlockOptions());
            $app->twig->addExtension(new FormExtension($renderer));

            $signUpInformation = $app->request->isGet() ? [] : array_merge($app->request->post(), $_FILES);

            $app->signUpForm->submit($signUpInformation);

            $isValid = $app->signUpValidator->validate($app->signUpForm);

            echo $app->twig->render('examples/validation.html.twig', [
                'signUp' => $view = $app->signUpForm->buildView(),
                'isValid' => $isValid,
            ]);
        })->via('GET', 'POST');

        $app->map('/captcha/:type', function ($type) use ($app) {

            if (!in_array($type, ['re-captcha', 'image'])) {
                $app->notFound();
            }

            $renderer = new FormRenderer(new FormTheme($app->twig, "layouts/required.html.twig"), new BlockOptions());
            $app->twig->addExtension(new FormExtension($renderer));

            if ($type === 'image') {
                $commentForm = new CommentForm(new ImageCaptchaAdapter($app->imageCaptcha));
            } else {
                $commentForm = new CommentForm(new ReCaptchaAdapter($app->reCaptcha));
            }

            $isValid = false;
            if ($app->request->isPost()) {
                if ($type === 'image') {
                    $app->commentFilter->addImageValidation($app->imageCaptcha);
                } else {
                    $app->commentFilter->addReCaptchaValidation($app->reCaptcha);
                }

                $commentForm->submit($app->request->post());

                $isValid = $app->commentValidator->validate($commentForm);
            }

            echo $app->twig->render('examples/captcha.html.twig', [
                'comment' => $view = $commentForm->buildView(),
                'isValid' => $isValid,
                'type' => $type,
            ]);
        })->via('GET', 'POST');

        $app->map('/csrf', function () use ($app) {

            $renderer = new FormRenderer(new FormTheme($app->twig, "layouts/required.html.twig"), new BlockOptions());
            $app->twig->addExtension(new FormExtension($renderer));

            $credentials = $app->request->isGet() ? [] : $app->request->post();

            $app->loginForm->submit($credentials);

            $isValid = $app->loginValidator->validate($app->loginForm);

            echo $app->twig->render('examples/csrf-elements.html.twig', [
                'login' => $view = $app->loginForm->buildView(),
                'isValid' => $isValid,
            ]);
        })->via('GET', 'POST');
    }
}
