<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ProductCatalog;

use Money\Money;

class ProductPricing
{
    /** @var integer */
    protected $productId;

    /** @var Money */
    protected $costPrice;

    /** @var Money */
    protected $salePrice;

    /**
     * @param integer $productId
     * @param Money $costPrice
     * @param Money $salePrice
     */
    public function __construct($productId, Money $costPrice, Money $salePrice)
    {
        $this->productId = $productId;
        $this->update($costPrice, $salePrice);
    }

    /**
     * @param Money $costPrice
     * @param Money $salePrice
     */
    public function update(Money $costPrice, Money $salePrice)
    {
        $this->costPrice = $costPrice;
        $this->salePrice = $salePrice;
    }

    /**
     * @return ProductPricingInformation
     */
    public function information()
    {
        $information = new ProductPricingInformation();
        $information->productId = $this->productId;
        $information->costPrice = $this->costPrice;
        $information->salePrice = $this->salePrice;

        return $information;
    }
}
