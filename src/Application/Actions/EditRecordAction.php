<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Application\Actions;

use Application\ProvidesFormRenderer;
use ExampleForms\ProductForm;
use ProductCatalog\Catalog;
use Twig_Environment as Twig;

class EditRecordAction
{
    use ProvidesFormRenderer;

    /** @var ProductForm */
    protected $productForm;

    /** @var Catalog */
    protected $catalog;

    /**
     * @param Twig $view
     * @param ProductForm $productForm
     * @param Catalog $catalog
     */
    public function __construct(Twig $view, ProductForm $productForm, Catalog $catalog)
    {
        $this->view = $view;
        $this->productForm = $productForm;
        $this->catalog = $catalog;
    }

    /**
     * Show the form to update a product's information
     */
    public function __invoke()
    {
        $this->configureFormRenderer('required');

        $this->productForm->addProductId();
        $this->productForm->populateFrom($product = $this->catalog->productOf($id = 1));

        echo $this->view->render('examples/edit-information.html.twig', [
            'form' => $this->productForm->buildView(),
        ]);
    }
}
