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

class AddProductForm extends Form
{
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
