<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Application\Actions;

use EasyForms\Bridges\Twig\BlockOptions;
use EasyForms\Bridges\Twig\FormExtension;
use EasyForms\Bridges\Twig\FormRenderer;
use EasyForms\Bridges\Twig\FormTheme;
use ExampleForms\TweetForm;
use Slim\Slim;
use Twig_Environment as Twig;

class ShowLayoutAction
{
    /** @var Twig */
    protected $view;

    /** @var TweetForm */
    protected $tweetForm;

    /**
     * @param Twig $view
     * @param TweetForm $tweetForm
     */
    public function __construct(Twig $view, TweetForm $tweetForm)
    {
        $this->view = $view;
        $this->tweetForm = $tweetForm;
    }

    /**
     * @param string $layout
     */
    public function __invoke($layout)
    {
        if (!in_array($layout, ['default', 'bootstrap3', 'required', 'optional'])) {
            Slim::getInstance()->notFound();
        }

        $renderer = new FormRenderer(new FormTheme($this->view, "layouts/$layout.html.twig"), new BlockOptions());
        $this->view->addExtension(new FormExtension($renderer));

        echo $this->view->render('examples/layout.html.twig', [
            'twitter' => $this->tweetForm->buildView(),
            'layoutName' => $layout,
        ]);
    }
}
