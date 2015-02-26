<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ProductCatalog;

class ProductPricingInformation
{
    /** @var integer */
    public $productId;

    /** @var \Money\Money */
    public $costPrice;

    /** @var \Money\Money */
    public $salePrice;
}
