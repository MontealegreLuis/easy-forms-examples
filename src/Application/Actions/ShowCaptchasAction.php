<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Application\Actions;

use Application\ProvidesFormRenderer;
use EasyForms\Bridges\Zend\Captcha\ImageCaptchaAdapter;
use EasyForms\Bridges\Zend\Captcha\ReCaptchaAdapter;
use EasyForms\Bridges\Zend\InputFilter\InputFilterValidator;
use ExampleForms\CommentForm;
use ExampleForms\Filters\CommentFilter;
use Slim\Http\Request;
use Slim\Slim;
use Twig_Environment as Twig;
use Zend\Captcha\Image;
use Zend\Captcha\ReCaptcha;

class ShowCaptchasAction
{
    use ProvidesFormRenderer;

    /** @var Image */
    protected $imageCaptcha;

    /** @var ReCaptcha */
    protected $reCaptcha;

    /** @var CommentFilter */
    protected $commentFilter;

    /** @var InputFilterValidator */
    protected $commentValidator;

    /**
     * @param Twig $view
     * @param Image $imageCaptcha
     * @param ReCaptcha $reCaptcha
     * @param CommentFilter $commentFilter
     * @param InputFilterValidator $commentValidator
     */
    public function __construct(
        Twig $view,
        Image $imageCaptcha,
        ReCaptcha $reCaptcha,
        CommentFilter $commentFilter,
        InputFilterValidator $commentValidator
    ) {
        $this->view = $view;
        $this->imageCaptcha = $imageCaptcha;
        $this->reCaptcha = $reCaptcha;
        $this->commentFilter = $commentFilter;
        $this->commentValidator = $commentValidator;
    }

    /**
     * @param Request $request
     * @param string $captchaType
     */
    public function __invoke(Request $request, $captchaType)
    {
        if (!in_array($captchaType, ['re-captcha', 'image'])) {
            Slim::getInstance()->notFound();
        }

        $this->configureFormRenderer('required');

        if ($captchaType === 'image') {
            $commentForm = new CommentForm(new ImageCaptchaAdapter($this->imageCaptcha));
        } else {
            $commentForm = new CommentForm(new ReCaptchaAdapter($this->reCaptcha));
        }

        $isValid = false;
        if ($request->isPost()) {
            if ($captchaType === 'image') {
                $this->commentFilter->addImageValidation($this->imageCaptcha);
            } else {
                $this->commentFilter->addReCaptchaValidation($this->reCaptcha);
            }

            $commentForm->submit($request->post());

            $isValid = $this->commentValidator->validate($commentForm);
        }

        echo $this->view->render('examples/captcha.html.twig', [
            'comment' => $commentForm->buildView(),
            'isValid' => $isValid,
            'type' => $captchaType,
        ]);
    }
}
