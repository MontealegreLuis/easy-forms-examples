<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ProductCatalog;

class CatalogSeeder
{
    /** @var array */
    protected $products;

    /**
     * @param array $products
     */
    public function __construct(array $products)
    {
        $this->products = $products;
    }

    /**
     * Populate the product catalog
     *
     * @param Catalog $catalog
     */
    public function seed(Catalog $catalog)
    {
        foreach ($this->products as $product) {
            $description = isset($product['description']) ? $product['description'] : null;
            $catalog->add(new Product($product['productId'], $product['unitPrice'], $product['name'], $description));
        }
    }
}
