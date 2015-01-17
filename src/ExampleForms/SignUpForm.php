<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ExampleForms;

use EasyForms\Elements\Captcha\CaptchaAdapter;
use EasyForms\Elements\Captcha;
use EasyForms\Elements\File;
use EasyForms\Elements\Password;
use EasyForms\Elements\Radio;
use EasyForms\Elements\Select;
use EasyForms\Elements\Text;
use EasyForms\Elements\TextArea;
use EasyForms\Form;

class SignUpForm extends Form
{
    /**
     * @param CaptchaAdapter $captchaAdapter
     */
    public function __construct(CaptchaAdapter $captchaAdapter)
    {
        $aboutYou = new TextArea('about_you');
        $aboutYou->makeOptional();

        $this
            ->add(new Text('name'))
            ->add(new Text('username'))
            ->add(new Password('password'))
            ->add($aboutYou)
            ->add(new Radio('gender', ['male' => 'Male', 'female' => 'Female']))
            ->add(new File('avatar'))
            ->add(new Select('newsletter', ['PRG' => 'Programming', 'TST' => 'Testing']))
            ->add(new Captcha('captcha', $captchaAdapter));
    }
}
