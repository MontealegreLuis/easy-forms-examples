<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright  Mandrágora Web-Based Systems 2015 (http://www.mandragora-web-systems.com)
 */
namespace ExampleForms\Filters;

use Zend\Captcha\Image;
use Zend\Captcha\ReCaptcha;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator\StringLength;

class CommentFilter extends InputFilter
{
    /**
     * Adds validation for the message
     */
    public function __construct()
    {
        $this
            ->add($this->buildMessageInput())
        ;
    }

    /**
     * @return Input
     */
    protected function buildMessageInput()
    {
        $message = new Input('message');
        $message->setContinueIfEmpty(true);

        $message
            ->getFilterChain()
            ->attach(new StringTrim())
            ->attach(new StripTags())
        ;

        $message
            ->getValidatorChain()
            ->attach(new StringLength([
                'max' => 2000,
            ]));

        return $message;
    }

    /**
     * @param Image $validator
     */
    public function addImageValidation(Image $validator)
    {
        $image = $this->buildCaptchaInput();

        $image
            ->getValidatorChain()
            ->attach($validator)
        ;

        $this->add($image);
    }

    /**
     * @param ReCaptcha $validator
     */
    public function addReCaptchaValidation(ReCaptcha $validator)
    {
        $reCapctha = $this->buildCaptchaInput();

        $reCapctha
            ->getValidatorChain()
            ->attach($validator)
        ;

        $this->add($reCapctha);
    }

    /**
     * @return Input
     */
    protected function buildCaptchaInput()
    {
        $captcha = new Input('captcha');
        $captcha->setContinueIfEmpty(true);

        return $captcha;
    }
}
