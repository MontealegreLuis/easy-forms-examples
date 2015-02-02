<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Application\Actions;

use Application\ProvidesFormRenderer;
use EasyForms\Bridges\Zend\InputFilter\InputFilterValidator;
use ExampleForms\AddToCartForm;
use ExampleForms\Configuration\AddToCartConfiguration;
use ExampleForms\Filters\AddToCartFilter;
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
