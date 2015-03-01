<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Application\Actions;

use EasyForms\Form;
use EasyForms\Validation\FormValidator;
use Slim\Http\Request;

class FormRequest
{
    /** @var Request */
    protected $request;

    /** @var Form */
    protected $form;

    /** @var FormValidator */
    protected $validator;

    /**
     * @param Request $request
     * @param Form $form
     * @param FormValidator $validator
     */
    public function __construct(Request $request, Form $form, FormValidator $validator)
    {
        $this->request = $request;
        $this->form = $form;
        $this->validator = $validator;
    }

    /**
     * @return boolean
     */
    public function isValid()
    {
        $this->form->submit($this->request->post());

        return $this->validator->validate($this->form);
    }

    /**
     * @return array
     */
    public function validValues()
    {
        return $this->form->values();
    }

    /**
     * @return \EasyForms\View\FormView
     */
    public function form()
    {
        return $this->form->buildView();
    }
}
