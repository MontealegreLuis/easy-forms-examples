<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Example\Filters;

use Example\Configuration\ProductPricingConfiguration;
use Zend\InputFilter\InputFilter;

class ProductPricingFilter extends InputFilter
{
    /**
     * Add filters for the Money elements
     */
    public function __construct()
    {
        $this
            ->add(new MoneyFilter('cost_price'), 'cost_price')
            ->add(new MoneyFilter('sale_price'), 'sale_price')
        ;
    }

    /**
     * Pass the currencies to be used as haystacks in the InArray validators
     *
     * @param ProductPricingConfiguration $configuration
     */
    public function configure(ProductPricingConfiguration $configuration)
    {
        $this->get('cost_price')->buildCurrencyInput($configuration->getCurrenciesHaystack());
        $this->get('sale_price')->buildCurrencyInput($configuration->getCurrenciesHaystack());
    }
}
