<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright  Mandrágora Web-Based Systems 2014 (http://www.mandragora-web-systems.com)
 */
namespace Example\Forms\Filters;

use Zend\Captcha\AdapterInterface;
use Zend\Filter\File\RenameUpload;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator\File\Extension;
use Zend\Validator\File\IsImage;
use Zend\Validator\File\MimeType;
use Zend\Validator\File\Size;
use Zend\Validator\NotEmpty;

class SignUpFilter extends InputFilter
{
    /**
     * @param AdapterInterface $captchaAdapter
     */
    public function __construct(AdapterInterface $captchaAdapter)
    {
        $this->add($this->buildAvatarInput());
        $this->add($this->buildCaptchaInput($captchaAdapter));
    }

    /**
     * @param AdapterInterface $captchaAdapter
     * @return Input
     */
    protected function buildCaptchaInput(AdapterInterface $captchaAdapter)
    {
        $captcha = new Input('captcha');
        $captcha->setContinueIfEmpty(true);

        $captcha
            ->getValidatorChain()
            ->attach($captchaAdapter);

        return $captcha;
    }

    /**
     * @return \Zend\InputFilter\Input
     */
    protected function buildAvatarInput()
    {
        $avatar = new Input('avatar');
        $avatar->setContinueIfEmpty(true);

        $avatar
            ->getValidatorChain()
            ->attach(new NotEmpty())
            ->attach(new Extension(['jpg', 'png']))
            ->attach(new IsImage(['magicFile' => '/etc/httpd/conf/magic']))
            ->attach(new MimeType(['image', 'magicFile' => '/etc/httpd/conf/magic']))
            ->attach(new Size(['max' => '20Mb']));

        $avatar
            ->getFilterChain()
            ->attach(new RenameUpload([
                'target'               => __DIR__ . '/../uploads',
                'use_upload_name'      => true,
                'use_upload_extension' => true,
                'overwrite'            => true,
            ]));

        return $avatar;
    }
}
