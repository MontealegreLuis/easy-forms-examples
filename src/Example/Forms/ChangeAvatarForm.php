<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Example\Forms;

use EasyForms\Elements\File;
use EasyForms\Form;

class ChangeAvatarForm extends Form
{
    public function __construct()
    {
        $this->add(new File('avatar'));
    }
}
