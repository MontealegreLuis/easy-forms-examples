<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ExampleForms\Filters;

use Forms\Bridges\Zend\InputFilter\Validator\CsrfValidator;
use Forms\Elements\Csrf\TokenProvider;
use Zend\Filter\StringTrim;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;
use Zend\Validator\Regex;

class LoginFilter extends InputFilter
{
    /**
     * Configure validation filters for fields:
     *
     * - username
     * - password
     * - csrf_token
     *
     * @param TokenProvider $tokenProvider
     */
    public function __construct(TokenProvider $tokenProvider)
    {
        $this->add($this->buildUsernameInput());
        $this->add($this->buildPasswordInput());
        $this->add($this->buildCsrfInput($tokenProvider));
    }

    /**
     * @return Input
     */
    protected function buildUsernameInput()
    {
        $username = new Input('username');

        $username
            ->getValidatorChain()
            ->attach(new NotEmpty())
            ->attach(new StringLength([
                'min' => 3,
            ]))
            ->attach(new Regex([
                'pattern' => '/^[a-z0-9_\.]*$/',
            ]));

        $username
            ->getFilterChain()
            ->attach(new StringTrim());

        return $username;
    }

    /**
     * @return Input
     */
    protected function buildPasswordInput()
    {
        $password = new Input('password');
        $password
            ->getValidatorChain()
            ->attach(new NotEmpty())
            ->attach(new StringLength([
                'min' => 8,
            ]));

        return $password;
    }

    /**
     * @param TokenProvider $tokenProvider
     * @return Input
     */
    protected function buildCsrfInput(TokenProvider $tokenProvider)
    {
        $csrf = new Input('csrf_token');
        $csrf->setContinueIfEmpty(true);

        $csrf
            ->getValidatorChain()
            ->attach(new CsrfValidator([
                'tokenProvider' => $tokenProvider,
                'tokenId' => '_login_csrf_token',
                'updateToken' => true,
            ]));

        return $csrf;
    }
}
