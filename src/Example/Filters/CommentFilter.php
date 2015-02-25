<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Example\Filters;

use Zend\Captcha\Image;
use Zend\Captcha\ReCaptcha;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;

class CommentFilter extends InputFilter
{
    /**
     * Add validation to the message text element
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

        $message
            ->getFilterChain()
            ->attach(new StringTrim())
            ->attach(new StripTags())
        ;

        $message
            ->getValidatorChain()
            ->attach(new NotEmpty())
            ->attach(new StringLength([
                'max' => 2000,
            ]))
        ;

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
        $reCaptcha = $this->buildCaptchaInput();

        $reCaptcha
            ->getValidatorChain()
            ->attach($validator)
        ;

        $this->add($reCaptcha);
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
