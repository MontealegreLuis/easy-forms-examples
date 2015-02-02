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

    /** @var string */
    protected $description;

    /**
     * @param integer $productId
     * @param float $unitPrice
     * @param string $name
     * @param string $description (optional)
     */
    public function __construct($productId, $unitPrice, $name, $description = null)
    {
        $this->productId = $productId;
        $this->unitPrice = $unitPrice;
        $this->name = $name;
        $this->description = $description;
    }

    /**
     * @return ProductInformation
     */
    public function information()
    {
        $information = new ProductInformation();
        $information->productId = $this->productId;
        $information->unitPrice = $this->unitPrice;
        $information->name = $this->name;
        $information->description = $this->description;

        return $information;
    }
}
