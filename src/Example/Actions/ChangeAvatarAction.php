<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Example\Actions;

use Application\Actions\ProvidesFormRenderer;
use Example\Forms\ChangeAvatarForm;
use Twig_Environment as Twig;

class ChangeAvatarAction
{
    use ProvidesFormRenderer;

    /** @var ChangeAvatarForm */
    protected $form;

    /**
     * @param Twig $view
     * @param ChangeAvatarForm $form
     */
    public function __construct(Twig $view, ChangeAvatarForm $form)
    {
        $this->view = $view;
        $this->form = $form;
    }

    public function changeAvatar()
    {
        $this->configureFormRenderer('required');

        echo $this->view->render('examples/upload-progress.html.twig', [
            'profile' => $this->form->buildView(),
        ]);
    }
}
