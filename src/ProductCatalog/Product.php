<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ProductCatalog;

class Product
{
    /** @var integer */
    protected $productId;

    /** @var float */
    protected $unitPrice;

    /** @var string */
    protected $name;

    /**
     * @param integer $productId
     * @param float $unitPrice
     * @param string $name
     */
    public function __construct($productId, $unitPrice, $name)
    {
        $this->productId = $productId;
        $this->unitPrice = $unitPrice;
        $this->name = $name;
    }

    /**
     * @return ProductInformation
     */
    public function information()
    {
        $state = new ProductInformation();
        $state->productId = $this->productId;
        $state->unitPrice = $this->unitPrice;
        $state->name = $this->name;

        return $state;
    }
}
