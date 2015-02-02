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
use Twig_Environment as Twig;

abstract class FormThemeAction
{
    /**
     * @param Twig $view
     * @param string $formLayout
     */
    protected function configureFormRenderer(Twig $view, $formLayout)
    {
        $renderer = new FormRenderer(new FormTheme($view, "layouts/$formLayout.html.twig"), new BlockOptions());
        $view->addExtension(new FormExtension($renderer));
    }
}
