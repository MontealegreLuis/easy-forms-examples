<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Example\Filters;

use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator\Digits;
use Zend\Validator\InArray;
use Zend\Validator\NotEmpty;

class MoneyFilter extends InputFilter
{
    /** @var string */
    protected $name;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
        $this->add($this->buildAmountInput());
    }

    /**
     * @return Input
     */
    protected function buildAmountInput()
    {
        $amount = new Input('amount');

        $amount
            ->getValidatorChain()
            ->attach(new NotEmpty(['type' => NotEmpty::INTEGER]))
            ->attach(new Digits())
        ;

        return $amount;
    }

    /**
     * @param array $validCurrencies
     * @return Input
     */
    public function buildCurrencyInput(array $validCurrencies)
    {
        $currency = new Input('currency');
        $currency->setContinueIfEmpty(true);

        $currency
            ->getValidatorChain()
            ->attach(new InArray([
                'haystack' => $validCurrencies,
            ]))
        ;

        $this->add($currency);
    }

    /**
     * Multiply the amount by 100, in order to assure that it contains only two decimal digits or less
     *
     * If the amount has 2 or less decimal digits, it will pass the Digit validation
     *
     * @param array|\Traversable $data
     * @return void
     */
    public function setData($data)
    {
        $data['original_amount'] = $data['amount'];
        $data['amount'] = $data['amount'] * 100;

        parent::setData($data);
    }

    /**
     * Use the original value with the decimal point
     *
     * @return array
     */
    public function getValues()
    {
        $values = parent::getValues();
        $values['amount'] = $this->data['original_amount'];

        return $values;
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        $messages = parent::getMessages();

        $moneyMessages = [];
        if (isset($messages['amount'])) {
            $moneyMessages = $messages['amount'];
            unset($messages['amount']);
        }
        if (isset($messages['currency'])) {
            $moneyMessages = array_merge($moneyMessages, $messages['currency']);
            unset($messages['currency']);
        }

        $messages[$this->name] = $moneyMessages;

        return $messages;
    }
}
