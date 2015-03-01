<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Example\Actions;

use Application\Actions\FormRequest;
use Application\Actions\ProvidesFormRenderer;
use EasyForms\Bridges\Zend\InputFilter\InputFilterValidator;
use Example\Forms\ProductPricingForm;
use ProductCatalog\Catalog\Catalog;
use ProductCatalog\Catalog\UpdatePricingRequest;
use Twig_Environment as Twig;

class CompositeElementAction
{
    use ProvidesFormRenderer;

    /** @var ProductPricingForm */
    protected $form;

    /** @var InputFilterValidator */
    protected $validator;

    /** @var Catalog */
    protected $catalog;

    /**
     * @param Twig $view
     * @param ProductPricingForm $form
     * @param InputFilterValidator $validator
     * @param Catalog $catalog
     */
    public function __construct(Twig $view, ProductPricingForm $form, InputFilterValidator $validator, Catalog $catalog)
    {
        $this->view = $view;
        $this->form = $form;
        $this->validator = $validator;
        $this->catalog = $catalog;
    }

    /**
     * Show the form to update a product's information
     */
    public function showForm()
    {
        $this->configureFormRenderer('required');

        $pricing = $this->catalog->pricingFor($productId = 1);
        $this->form->populateFrom($pricing->information());

        echo $this->view->render('examples/composite-elements.html.twig', [
            'form' => $this->form->buildView(),
        ]);
    }

    /**
     * @param FormRequest $request
     */
    public function processForm(FormRequest $request)
    {
        $this->configureFormRenderer('required');

        if ($isValid = $request->isValid()) {
            $pricingRequest = new UpdatePricingRequest($request->validValues());
            $pricing = $this->catalog->pricingFor($productId = 1);
            $pricing->update($pricingRequest->costPrice, $pricingRequest->salePrice);
            $this->catalog->updatePrice($pricing);
        }

        echo $this->view->render('examples/composite-elements.html.twig', [
            'form' => $request->form(),
            'isValid' => $isValid,
        ]);
    }
}
