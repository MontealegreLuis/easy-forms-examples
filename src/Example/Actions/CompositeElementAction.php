<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Example\Actions;

use Application\Actions\ProvidesFormRenderer;
use EasyForms\Bridges\Zend\InputFilter\InputFilterValidator;
use Example\Forms\ProductPricingForm;
use Money\Currency;
use Money\Money;
use ProductCatalog\Catalog\Catalog;
use Slim\Http\Request;
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
     *
     * @param Request $request
     */
    public function showCompositeElement(Request $request)
    {
        $this->configureFormRenderer('required');

        $isValid = false;
        $pricing = $this->catalog->pricingFor($productId = 1);

        if ($request->isPost()) {
            $this->form->submit($request->post());
            if ($isValid = $this->validator->validate($this->form)) {
                $information = $this->form->values();
                $pricing->update(
                    new Money((int) $information['cost_price']['amount'], new Currency($information['cost_price']['currency'])),
                    new Money((int) $information['sale_price']['amount'], new Currency($information['sale_price']['currency']))
                );
                $this->catalog->updatePrice($pricing);
            }
        }

        $this->form->populateFrom($pricing->information());

        echo $this->view->render('examples/composite-elements.html.twig', [
            'isValid' => $isValid,
            'form' => $this->form->buildView(),
        ]);
    }
}
