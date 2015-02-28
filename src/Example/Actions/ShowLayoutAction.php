<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Example\Actions;

use Application\Actions\ProvidesFormRenderer;
use Example\Forms\ProductForm;
use Example\Forms\TweetForm;
use Slim\Slim;
use Twig_Environment as Twig;

class ShowLayoutAction
{
    use ProvidesFormRenderer;

    /** @var TweetForm */
    protected $tweetForm;

    /** @var ProductForm */
    protected $productForm;

    /**
     * @param Twig $view
     * @param TweetForm $tweetForm
     * @param ProductForm $productForm
     */
    public function __construct(Twig $view, TweetForm $tweetForm, ProductForm $productForm)
    {
        $this->view = $view;
        $this->tweetForm = $tweetForm;
        $this->productForm = $productForm;
    }

    /**
     * Select a different form and layout depending on the value of the 'layout' argument
     *
     * @param string $layout
     */
    public function switchLayout($layout)
    {
        if (!in_array($layout, ['default', 'bootstrap3', 'required', 'optional', 'inline'])) {
            Slim::getInstance()->notFound();
        }

        $template = 'examples/layout.html.twig';
        $formLayout = $layout;
        $vars = [
            'layoutName' => $formLayout,
            'formTemplate' => 'tweet',
            'form' => $this->tweetForm->buildView()
        ];

        if ($layout === 'inline') {
            $template = 'examples/inline-layout.html.twig';
            $formLayout = 'optional'; // Switch to the 'optional' form layout because both examples use the same form
        }

        if ($formLayout === 'optional') {
            $vars['form'] = $this->productForm->buildView();
            $vars['formTemplate'] = 'product';
        }

        $this->configureFormRenderer($formLayout);

        echo $this->view->render($template, $vars);
    }
}
