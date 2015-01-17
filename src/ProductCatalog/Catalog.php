<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright  Mandrágora Web-Based Systems 2015 (http://www.mandragora-web-systems.com)
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
     * @return ProductState[]
     */
    public function all()
    {
        $products = [];

        /** @var Product $product */
        foreach ($this->products as $product) {
            $products[] = $product->state();
        }
        return $products;
    }
}
