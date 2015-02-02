<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ExampleForms;

use EasyForms\Elements\Captcha;
use EasyForms\Elements\Captcha\CaptchaAdapter;
use EasyForms\Elements\TextArea;
use EasyForms\Form;

class CommentForm extends Form
{
    /**
     * Form with the following fields
     *
     * - message (a text area element)
     * - captcha (a captcha element, depending on the adapter it can be an image or a ReCaptcha captcha)
     *
     * @param CaptchaAdapter $adapter
     */
    public function __construct(CaptchaAdapter $adapter)
    {
        $this
            ->add(new TextArea('message'))
            ->add(new Captcha('captcha', $adapter))
        ;
    }
}
