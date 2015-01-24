<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ExampleForms;

use EasyForms\Elements\TextArea;
use EasyForms\Form;

/**
 * Simple form to demonstrate the usage of form themes
 */
class TweetForm extends Form
{
    /**
     * This form only contains a text area named 'tweet'
     */
    public function __construct()
    {
        $this
            ->add(new TextArea('tweet'));
    }
}
