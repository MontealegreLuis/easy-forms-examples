<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ExampleForms;

use EasyForms\Form;
use ExampleForms\Configuration\ProductPricingConfiguration;
use ExampleForms\Elements\Money;

class ProductPricingForm extends Form
{
    /**
     * This form contains the following elements
     *
     * - A Money element representing the product's cost price
     * - A Money element representing the product's sale price
     */
    public function __construct()
    {
        $this
            ->add(new Money('cost_price'))
            ->add(new Money('sale_price'))
        ;
    }

    /**
     * Add the list of valid currencies to the choices in the money elements
     *
     * @param ProductPricingConfiguration $configuration
     */
    public function configure(ProductPricingConfiguration $configuration)
    {
        $this->get('cost_price')->setCurrencyChoices($configuration->getCurrencyChoices());
        $this->get('sale_price')->setCurrencyChoices($configuration->getCurrencyChoices());
    }
}
