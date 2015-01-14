<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ExampleForms;

use ExampleForms\Configuration\AddToCartConfiguration;
use Forms\Elements\Select;
use Forms\Elements\Text;
use Forms\Form;

class AddToCartForm extends Form
{
    /**
     * Form with the following fields
     *
     * - product (a select element with all the products in the catalog)
     * - quantity (a text element with the amount of products to be added to the cart)
     */
    public function __construct()
    {
        $this
            ->add(new Select('product'))
            ->add(new Text('quantity'));
    }

    /**
     * Pass all the available products from catalog to the form select options
     *
     * @param AddToCartConfiguration $configuration
     */
    public function configure(AddToCartConfiguration $configuration)
    {
        /** @var Select $product */
        $product = $this->get('product');

        $product->setChoices($configuration->getProductOptions());
    }
}
