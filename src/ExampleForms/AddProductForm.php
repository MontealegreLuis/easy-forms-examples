<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ExampleForms;

use EasyForms\Elements\Text;
use EasyForms\Elements\TextArea;
use EasyForms\Form;

/**
 * This form is used to demonstrate the combination of a custom layout with an inline layout
 */
class AddProductForm extends Form
{
    /**
     * The elements of this form are as follows:
     *
     * - name        The product's name (a text input)
     * - description The product's description (an optional text area)
     * - price       The product's price (a text input)
     */
    public function __construct()
    {
        $description = new TextArea('description');
        $description->makeOptional();

        $this
            ->add(new Text('name'))
            ->add($description)
            ->add(new Text('price'));
    }
}
