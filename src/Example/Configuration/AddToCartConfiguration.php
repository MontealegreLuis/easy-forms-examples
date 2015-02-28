<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Example\Configuration;

use ProductCatalog\Catalog\Catalog;
use ProductCatalog\Products\ProductInformation;


class AddToCartConfiguration
{
    /** @var Catalog */
    protected $catalog;

    /** @var array */
    protected $productOptions;

    /**
     * @param Catalog $catalog
     */
    public function __construct(Catalog $catalog)
    {
        $this->catalog = $catalog;
    }

    /**
     * @return array
     */
    public function getProductOptions()
    {
        $this->productOptions = [];
        array_map(function (ProductInformation $product) use (&$options) {
            $this->productOptions[$product->productId] = "{$product->name}, \${$product->unitPrice}";
        }, $this->catalog->all());

        return $this->productOptions;
    }

    /**
     * @return array
     */
    public function getProductsIds()
    {
        if (!$this->productOptions) {
            $this->getProductOptions();
        }

        return array_keys($this->productOptions);
    }
}
