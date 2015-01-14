<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ExampleForms\Filters;

use Example\Forms\Configuration\AddToCartConfiguration;
use Zend\Filter\Int;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator\Digits;
use Zend\Validator\InArray;
use Zend\Validator\NotEmpty;

class AddToCartFilter extends InputFilter
{
    /**
     * Configure validation filters for fields:
     *
     * - product
     * - quantity
     */
    public function __construct()
    {
        $this->add($this->buildProductInput());
        $this->add($this->buildQuantityInput());
    }

    /**
     * @param AddToCartConfiguration $configuration
     */
    public function configure(AddToCartConfiguration $configuration)
    {
        $product = $this->get('product');
        $product
            ->getValidatorChain()
            ->attach(new InArray([
                'haystack' => $configuration->getProductsIds(),
            ]));
    }

    /**
     * @return Input
     */
    protected function buildProductInput()
    {
        $product = new Input('product');

        $product
            ->getValidatorChain()
            ->attach(new NotEmpty());

        $product
            ->getFilterChain()
            ->attach(new Int());

        return $product;
    }

    /**
     * @return Input
     */
    protected function buildQuantityInput()
    {
        $quantity = new Input('quantity');
        $quantity
            ->getValidatorChain()
            ->attach(new NotEmpty())
            ->attach(new Digits());

        $quantity
            ->getFilterChain()
            ->attach(new Int());

        return $quantity;
    }
}
