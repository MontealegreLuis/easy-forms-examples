<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Example\Elements;

use EasyForms\Elements\Element;
use EasyForms\Elements\Select;
use EasyForms\Elements\Text;
use EasyForms\View\ElementView;
use Example\Views\MoneyView;

class Money extends Element
{
    /** @var Text */
    protected $amount;

    /** @var Select */
    protected $currency;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        parent::__construct($name);
        $this->amount = new Text("{$name}[amount]");
        $this->currency = new Select("{$name}[currency]");
    }

    /**
     * @param array $currencies
     */
    public function setCurrencyChoices(array $currencies)
    {
        $this->currency->setChoices($currencies);
    }

    /**
     * @param $value
     */
    public function setValue($value)
    {
        $this->amount->setValue($value['amount']);
        $this->currency->setValue($value['currency']);
    }

    /**
     * @return array
     */
    public function value()
    {
        return [
            'amount' => $this->amount->value(),
            'currency' => $this->currency->value(),
        ];
    }

    /**
     * @param ElementView $view
     * @return MoneyView
     */
    public function buildView(ElementView $view = null)
    {
        $view = new MoneyView();

        /** @var MoneyView $view */
        $view = parent::buildView($view);

        $view->amount = $this->amount->buildView();
        $view->currency = $this->currency->buildView();
        $view->block = 'money';

        return $view;
    }
}
