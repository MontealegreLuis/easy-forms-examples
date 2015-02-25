<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Example\Filters;

use Zend\Filter\File\RenameUpload;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator\Explode;
use Zend\Validator\File\Extension;
use Zend\Validator\File\IsImage;
use Zend\Validator\File\MimeType;
use Zend\Validator\File\Size;
use Zend\Validator\Identical;
use Zend\Validator\InArray;
use Zend\Validator\NotEmpty;
use Zend\Validator\Regex;
use Zend\Validator\StringLength;

class SignUpFilter extends InputFilter
{
    /**
     * @param string $uploadsDirectory
     */
    public function __construct($uploadsDirectory)
    {
        $this
            ->add($this->buildUsernameInput())
            ->add($this->buildPasswordInput())
            ->add($this->buildConfirmPasswordInput())
            ->add($this->buildDescriptionInput())
            ->add($this->buildAvatarInput($uploadsDirectory))
            ->add($this->buildGenderInput())
            ->add($this->buildLanguagesInput())
            ->add($this->buildTopicsInput())
            ->add($this->buildRoleInput())
            ->add($this->buildTermsInput())
        ;
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
            ]))
        ;

        $username
            ->getFilterChain()
            ->attach(new StringTrim())
        ;

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
            ]))
        ;

        return $password;
    }

    /**
     * @return Input
     */
    protected function buildConfirmPasswordInput()
    {
        $confirmPassword = new Input('confirm_password');
        $confirmPassword
            ->getValidatorChain()
            ->attach(new NotEmpty())
            ->attach(new StringLength([
                'min' => '8'
            ]))
            ->attach(new Identical('password'))
        ;

        return $confirmPassword;
    }

    /**
     * This field is optional
     *
     * @return Input
     */
    protected function buildDescriptionInput()
    {
        $description = new Input('description');

        $description->setAllowEmpty(true);

        $description
            ->getValidatorChain()
            ->attach(new StringLength([
                'max' => '1000'
            ]))
        ;

        $description
            ->getFilterChain()
            ->attach(new StringTrim())
            ->attach(new StripTags())
        ;

        return $description;
    }

    /**
     * @return Input
     */
    protected function buildGenderInput()
    {
        $gender = new Input('gender');

        $gender->setContinueIfEmpty(true);

        $gender
            ->getValidatorChain()
            ->attach(new InArray([
                'haystack' => ['male', 'female'],
            ]))
        ;

        return $gender;
    }

    /**
     * @param string $uploadsDirectory
     * @return \Zend\InputFilter\Input
     */
    protected function buildAvatarInput($uploadsDirectory)
    {
        $avatar = new Input('avatar');
        $avatar->setContinueIfEmpty(true);

        $avatar
            ->getValidatorChain()
            ->attach(new NotEmpty())
            ->attach(new Extension(['jpg', 'png']))
            ->attach(new IsImage())
            ->attach(new MimeType(['image']))
            ->attach(new Size(['max' => '20Mb']))
        ;

        $avatar
            ->getFilterChain()
            ->attach(new RenameUpload([
                'target' => $uploadsDirectory,
                'use_upload_name' => true,
                'use_upload_extension' => true,
                'overwrite' => true,
            ]))
        ;

        return $avatar;
    }

    /**
     * @return Input
     */
    protected function buildLanguagesInput()
    {
        $languages = new Input('languages');

        $languages->setContinueIfEmpty(true);

        $languages
            ->getValidatorChain()
            ->attach(new Explode([
                'validator' => new InArray(['haystack' => ['J', 'S', 'C', 'P', 'JS', 'R', ]])
            ]))
        ;

        return $languages;
    }

    /**
     * @return Input
     */
    protected function buildTopicsInput()
    {
        $topics = new Input('topics');

        $topics->setContinueIfEmpty(true);

        $topics
            ->getValidatorChain()
            ->attach(new Explode([
                'validator' => new InArray(['haystack' => ['u', 's', 't']]),
            ]))
        ;

        return $topics;
    }

    /**
     * @return Input
     */
    protected function buildRoleInput()
    {
        $role = new Input('role');

        $role->setContinueIfEmpty(true);

        $role
            ->getValidatorChain()
            ->attach(new InArray([
                'haystack' => ['b', 'f', 's'],
            ]))
        ;

        return $role;
    }

    /**
     * @return Input
     */
    protected function buildTermsInput()
    {
        $terms = new Input('terms');

        $terms->setContinueIfEmpty(true);

        $terms
            ->getValidatorChain()
            ->attach(new InArray([
                'haystack' => ['accept'],
            ]))
        ;

        return $terms;
    }
}
