<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ProductCatalog;

use SplObjectStorage;

class Catalog
{
    /** @var SplObjectStorage */
    protected $products;

    /**
     * Initialize an empty list of products
     */
    public function __construct()
    {
        $this->products = new SplObjectStorage();
    }

    /**
     * @param Product $product
     */
    public function add(Product $product)
    {
        $this->products->attach($product);
    }

    /**
     * @param integer $productId
     * @return ProductInformation
     */
    public function productOf($productId)
    {
        /** @var Product $product */
        foreach ($this->products as $product) {
            $information = $product->information();
            if ($information->productId === $productId) {
                return $information;
            }
        }
    }

    /**
     * @return ProductInformation[]
     */
    public function all()
    {
        $products = [];

        /** @var Product $product */
        foreach ($this->products as $product) {
            $products[] = $product->information();
        }

        return $products;
    }
}
