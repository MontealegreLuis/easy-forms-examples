<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Application\Actions;

use Application\ProvidesFormRenderer;
use EasyForms\Bridges\Zend\InputFilter\InputFilterValidator;
use Example\Forms\ProductPricingForm;
use Slim\Http\Request;
use Twig_Environment as Twig;

class CompositeElementAction
{
    use ProvidesFormRenderer;

    /** @var ProductPricingForm */
    protected $form;

    /** @var InputFilterValidator */
    protected $validator;

    /**
     * @param Twig $view
     * @param ProductPricingForm $form
     * @param InputFilterValidator $validator
     */
    public function __construct(Twig $view, ProductPricingForm $form, InputFilterValidator $validator)
    {
        $this->view = $view;
        $this->form = $form;
        $this->validator = $validator;
    }

    /**
     * Show the form to update a product's information
     *
     * @param Request $request
     */
    public function __invoke(Request $request)
    {
        $this->configureFormRenderer('required');

        $this->form->submit($pricingInformation = $request->isGet() ? [] : $request->post());

        echo $this->view->render('examples/composite-elements.html.twig', [
            'isValid' => $this->validator->validate($this->form),
            'form' => $this->form->buildView(),
        ]);
    }
}
