<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright  Mandrágora Web-Based Systems 2015 (http://www.mandragora-web-systems.com)
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
    function __construct($productId, $unitPrice, $name)
    {
        $this->productId = $productId;
        $this->unitPrice = $unitPrice;
        $this->name = $name;
    }

    /**
     * @return ProductState
     */
    public function state()
    {
        $state = new ProductState();
        $state->productId = $this->productId;
        $state->unitPrice = $this->unitPrice;
        $state->name = $this->name;

        return $state;
    }
}
