<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ProductCatalog\Catalog;

use Money\Currency;
use Money\Money;

class UpdatePricingRequest
{
    /** @var Money */
    public $costPrice;

    /** @var Money */
    public $salePrice;

    /**
     * @param array $pricing
     */
    public function __construct(array $pricing)
    {
        $this->costPrice = new Money(
            (int) round($pricing['cost_price']['amount'] * 100), new Currency($pricing['cost_price']['currency'])
        );
        $this->salePrice = new Money(
            (int) round($pricing['sale_price']['amount'] * 100), new Currency($pricing['sale_price']['currency'])
        );
    }
}
