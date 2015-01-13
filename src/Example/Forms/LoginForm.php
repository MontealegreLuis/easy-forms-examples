<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright  Mandrágora Web-Based Systems 2014 (http://www.mandragora-web-systems.com)
 */
namespace Example\Forms;

use Forms\Elements\Csrf\TokenProvider;
use Forms\Elements\Text;
use Forms\Elements\Password;
use Forms\Elements\Checkbox;
use Forms\Elements\Csrf;
use Forms\Form;

class LoginForm extends Form
{
    /**
     * @param TokenProvider $csrfTokenProvider
     */
    public function __construct(TokenProvider $csrfTokenProvider)
    {
        $this
            ->add(new Text('username'))
            ->add(new Password('password'))
            ->add(new Checkbox('remember_me', 'remember_me'))
            ->add(new Csrf('csrf_token', '_login_csrf_token', $csrfTokenProvider));
    }
}
