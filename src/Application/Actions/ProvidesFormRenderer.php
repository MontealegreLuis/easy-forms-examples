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

trait ProvidesFormRenderer
{
    /** @var \Twig_Environment */
    protected $view;

    /**
     * @param string $formLayout
     */
    protected function configureFormRenderer($formLayout)
    {
        $renderer = new FormRenderer(new FormTheme($this->view, "layouts/$formLayout.html.twig"), new BlockOptions());
        $this->view->addExtension(new FormExtension($renderer));
    }
}
