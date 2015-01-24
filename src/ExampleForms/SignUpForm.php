<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ExampleForms;

use EasyForms\Elements\Captcha;
use EasyForms\Elements\Checkbox;
use EasyForms\Elements\File;
use EasyForms\Elements\MultiCheckbox;
use EasyForms\Elements\Password;
use EasyForms\Elements\Radio;
use EasyForms\Elements\Select;
use EasyForms\Elements\Text;
use EasyForms\Elements\TextArea;
use EasyForms\Form;

/**
 * This form is used to demonstrate all form elements types
 */
class SignUpForm extends Form
{
    /**
     *
     */
    public function __construct()
    {
        $aboutYou = new TextArea('about_you');
        $aboutYou->makeOptional();

        $languages = new Select('languages', [
            'Compiled' => [
                'J' => 'Java',
                'S' => 'Scala',
                'C' => 'C#',
            ],
            'Scripting' => [
                'P' => 'PHP',
                'JS' => 'JavaScript',
                'R' => 'Ruby',
            ],
        ]);
        $languages->enableMultipleSelection();

        $this
            ->add(new Text('username'))
            ->add(new Password('password'))
            ->add(new Password('confirm_password'))
            ->add($aboutYou)
            ->add(new Radio('gender', ['male' => 'Male', 'female' => 'Female']))
            ->add(new File('avatar'))
            ->add(new MultiCheckbox('topics', ['u' => 'Usability', 's' => 'Security', 't' => 'Testing']))
            ->add($languages)
            ->add(new Select('role', [
                'b' => 'Backend developer',
                'f' => 'Frontend developer',
                's' => 'Full stack developer'
            ]))
            ->add(new Checkbox('terms', 'accept'));
    }
}
