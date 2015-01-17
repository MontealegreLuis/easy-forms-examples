<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ExampleForms;

use EasyForms\Elements\TextArea;
use EasyForms\Form;

class TweetForm extends Form
{
    public function __construct()
    {
        $this
            ->add(new TextArea('tweet'));
    }
}
