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

        $app->map('/database', function () use ($app) {
            $renderer = new FormRenderer(new FormTheme($app->twig, "layouts/required.html.twig"), new BlockOptions());
            $app->twig->addExtension(new FormExtension($renderer));

            $app->addToCartForm->configure($app->addToCartConfiguration);
            $app->addToCartFilter->configure($app->addToCartConfiguration);

            $orderItemInformation = $app->request->isGet() ? [] : $app->request->post();

            $app->addToCartForm->submit($orderItemInformation);

            $isValid = $app->addToCartValidator->validate($app->addToCartForm);

            echo $app->twig->render('examples/database.html.twig', [
                'cart' => $view = $app->addToCartForm->buildView(),
                'isValid' => $isValid,
            ]);
        })->via('GET', 'POST');

        $app->map('/edit-information', function () use ($app) {
            $renderer = new FormRenderer(new FormTheme($app->twig, "layouts/required.html.twig"), new BlockOptions());
            $app->twig->addExtension(new FormExtension($renderer));

            $app->productForm->addProductId();
            $app->productForm->populateFrom($product = $app->catalog->productOf($id = 1));

            echo $app->twig->render('examples/edit-information.html.twig', [
                'product' => $view = $app->productForm->buildView(),
            ]);
        })->via('GET', 'POST');
    }
}
