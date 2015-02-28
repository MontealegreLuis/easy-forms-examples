<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Example\Forms;

use EasyForms\Elements\Hidden;
use EasyForms\Elements\Text;
use EasyForms\Elements\TextArea;
use EasyForms\Form;
use ProductCatalog\Products\ProductInformation;

/**
 * This form is used to demonstrate the combination of a custom layout with an inline layout
 */
class ProductForm extends Form
{
    /**
     * The elements of this form are as follows:
     *
     * - name        The product's name (a text input)
     * - description The product's description (an optional text area)
     * - price       The product's price (a text input)
     */
    public function __construct()
    {
        $description = new TextArea('description');
        $description->makeOptional();

        $this
            ->add(new Text('name'))
            ->add($description)
            ->add(new Text('unitPrice'))
        ;
    }

    /**
     * We need the hidden element when we edit an existing product's information
     */
    public function addProductId()
    {
        $this->add(new Hidden('productId'));
    }

    /**
     * Pass the current product's information to the form so the user can edit it
     *
     * @param ProductInformation $product
     */
    public function populateFrom(ProductInformation $product)
    {
        $this->populate([
            'productId' => $product->productId,
            'unitPrice' => $product->unitPrice,
            'name' => $product->name,
            'description' => $product->description,
        ]);
    }
}
