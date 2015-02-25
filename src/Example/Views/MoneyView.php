<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Example\Views;

use EasyForms\View\ElementView;

class MoneyView extends ElementView
{
    /** @var ElementView */
    public $amount;

    /** @var SelectView */
    public $currency;
}
