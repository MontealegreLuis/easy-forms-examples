<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Example\Actions;

use Twig_Environment as Twig;

class IndexAction
{
    /** @var Twig */
    protected $view;

    /**
     * @param Twig $view
     */
    public function __construct(Twig $view)
    {
        $this->view = $view;
    }

    /**
     * Show the easy forms examples index
     */
    public function __invoke()
    {
        echo $this->view->render('index.html.twig');
    }
}
