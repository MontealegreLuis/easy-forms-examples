<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Example\Actions;

use Application\Actions\ProvidesFormRenderer;
use EasyForms\Bridges\Zend\InputFilter\InputFilterValidator;
use Example\Configuration\AddToCartConfiguration;
use Example\Filters\AddToCartFilter;
use Example\Forms\AddToCartForm;
use Slim\Http\Request;
use Twig_Environment as Twig;

class FormConfigurationAction
{
    use ProvidesFormRenderer;

    /** @var AddToCartForm */
    protected $addToCartForm;

    /** @var AddToCartFilter */
    protected $addToCartFilter;

    /** @var AddToCartConfiguration */
    protected $addToCartConfiguration;

    /** @var InputFilterValidator */
    protected $addToCartValidator;

    /**
     * @param Twig $view
     * @param AddToCartForm $addToCartForm
     * @param AddToCartFilter $addToCartFilter
     * @param AddToCartConfiguration $addToCartConfiguration
     * @param InputFilterValidator $addToCartValidator
     */
    public function __construct(
        Twig $view,
        AddToCartForm $addToCartForm,
        AddToCartFilter $addToCartFilter,
        AddToCartConfiguration $addToCartConfiguration,
        InputFilterValidator $addToCartValidator
    ) {
        $this->view = $view;
        $this->addToCartForm = $addToCartForm;
        $this->addToCartFilter = $addToCartFilter;
        $this->addToCartConfiguration = $addToCartConfiguration;
        $this->addToCartValidator = $addToCartValidator;
    }

    /**
     * Validate the form values and show it with the corresponding error messages
     *
     * The form is configured using the values retrieved by the product's catalog
     *
     * @param Request $request
     */
    public function __invoke(Request $request)
    {
        $this->configureFormRenderer('required');

        $this->addToCartForm->configure($this->addToCartConfiguration);
        $this->addToCartFilter->configure($this->addToCartConfiguration);

        $orderItemInformation = $request->isGet() ? [] : $request->post();

        $this->addToCartForm->submit($orderItemInformation);

        $isValid = $this->addToCartValidator->validate($this->addToCartForm);

        echo $this->view->render('examples/database.html.twig', [
            'cart' => $this->addToCartForm->buildView(),
            'isValid' => $isValid,
        ]);
    }
}
