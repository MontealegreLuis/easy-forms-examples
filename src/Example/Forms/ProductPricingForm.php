<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Example\Forms;

use EasyForms\Form;
use Example\Configuration\ProductPricingConfiguration;
use Example\Elements\Money;
use ProductCatalog\ProductPricingInformation;

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

    /**
     * @param ProductPricingInformation $pricing
     */
    public function populateFrom(ProductPricingInformation $pricing)
    {
        $this->populate([
            'cost_price' => [
                'amount' => $pricing->costPrice->getAmount() / 100,
                'currency' => $pricing->costPrice->getCurrency()->getName(),
            ],
            'sale_price' => [
                'amount' => $pricing->salePrice->getAmount() / 100,
                'currency' => $pricing->salePrice->getCurrency()->getName(),
            ],
        ]);
    }
}
