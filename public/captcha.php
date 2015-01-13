<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright  Mandrágora Web-Based Systems 2014 (http://www.mandragora-web-systems.com)
 */
require __DIR__ . '/../vendor/autoload.php';

$captcha = new \Zend\Captcha\Dumb(); //session, eventmanager
$element = new \Zend\Form\Element\Captcha();
$element->setName('captcha');
$element->setCaptcha($captcha);
$captchaHelper = new \Zend\Form\View\Helper\Captcha\Dumb();

//word()
echo $captchaHelper->render($element);

$captcha = new \Zend\Captcha\Figlet();
$element = new \Zend\Form\Element\Captcha();
$element->setName('captcha');
$element->setCaptcha($captcha);
$captchaHelper = new \Zend\Form\View\Helper\Captcha\Figlet();

//getFliget(),
echo $captchaHelper->render($element);

$captcha = new \Zend\Captcha\Image();
$captcha->setFont(__DIR__ . '/../fonts/Monaco.ttf');
$captcha->setImgDir(__DIR__ . '/../images/captcha');
$captcha->setImgUrl('../images/captcha');

$element = new \Zend\Form\Element\Captcha();
$element->setName('captcha');
$element->setCaptcha($captcha);

$captchaHelper = new \Zend\Form\View\Helper\Captcha\Image();
echo $captchaHelper->render($element);
