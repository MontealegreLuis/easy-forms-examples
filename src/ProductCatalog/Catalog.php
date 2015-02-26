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

    /** @var SplObjectStorage */
    protected $prices;

    /**
     * Initialize an empty list of products
     */
    public function __construct()
    {
        $this->products = new SplObjectStorage();
        $this->prices = new SplObjectStorage();
    }

    /**
     * @param Product $product
     */
    public function add(Product $product)
    {
        $this->products->attach($product);
    }

    /**
     * @param ProductPricing $price
     */
    public function addPrice(ProductPricing $price)
    {
        $this->prices[$price] = $price;
    }

    /**
     * @param ProductPricing $price
     */
    public function updatePrice(ProductPricing $price)
    {
        $this->prices[$price] = $price;
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
     * @param $productId
     * @return ProductPricing
     */
    public function pricingFor($productId)
    {
        /** @var ProductPricing $price */
        foreach ($this->prices as $price) {
            $information = $price->information();
            if ($information->productId === $productId) {
                return $price;
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

    /**
     * Return the ISO codes that can be assigned to a product's price
     *
     * @return array
     */
    public function validCurrencies()
    {
        return ['MXN', 'USD'];
    }
}
