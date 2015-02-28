<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Example\Configuration;

use ProductCatalog\Catalog\Catalog;

class ProductPricingConfiguration
{
    /**
     * @var Catalog
     */
    protected $catalog;

    public function __construct(Catalog $catalog)
    {
        $this->catalog = $catalog;
    }

    /**
     * @return array
     */
    public function getCurrencyChoices()
    {
        return array_combine($this->catalog->validCurrencies(), $this->catalog->validCurrencies());
    }

    /**
     * @return array
     */
    public function getCurrenciesHaystack()
    {
        return $this->catalog->validCurrencies();
    }
}
