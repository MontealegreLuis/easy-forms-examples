<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Example\Actions;

use Application\Actions\ProvidesFormRenderer;
use EasyForms\Bridges\Zend\InputFilter\InputFilterValidator;
use Example\Forms\LoginForm;
use Slim\Http\Request;
use Twig_Environment as Twig;

class ShowCsrfTokensAction
{
    use ProvidesFormRenderer;

    /** @var LoginForm */
    protected $loginForm;

    /** @var InputFilterValidator */
    protected $loginValidator;

    /**
     * @param Twig $view
     * @param LoginForm $loginForm
     * @param InputFilterValidator $loginValidator
     */
    public function __construct(Twig $view, LoginForm $loginForm, InputFilterValidator $loginValidator)
    {
        $this->view = $view;
        $this->loginForm = $loginForm;
        $this->loginValidator = $loginValidator;
    }

    /**
     * Show and validate a form with a hidden element which value is a CSRF token
     *
     * @param Request $request
     */
    public function showCsrfToken(Request $request)
    {
        $this->configureFormRenderer('required');

        $credentials = $request->isGet() ? [] : $request->post();

        $this->loginForm->submit($credentials);

        $isValid = $this->loginValidator->validate($this->loginForm);

        echo $this->view->render('examples/csrf-elements.html.twig', [
            'login' => $this->loginForm->buildView(),
            'isValid' => $isValid,
        ]);
    }
}
