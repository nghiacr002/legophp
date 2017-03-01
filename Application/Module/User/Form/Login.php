<?php

namespace APP\Application\Module\User\Form;

use APP\Engine\HTML\Form as HtmlForm;
use APP\Engine\HTML\Input;
use APP\Engine\Validator;
use APP\Engine\HTML\Form;

class Login extends HtmlForm
{

    public function __construct()
    {
        parent::__construct();
        $this->_aElements = array();

        $oInput = new Input();
        $oInput->setName("email");
        $oInput->setValue($this->app->request->get('email'));
        $oInput->required(true);
        $aPostData = $this->app->request->getParams();
        $mValidator = new Validator($aPostData);
        $mValidator->rule('email', 'email');
        $oInput->validator($mValidator);
        $this->addElement($oInput);

        $oInput = new Input();
        $oInput->setName("password");
        $oInput->setValue($this->app->request->get('password'));
        $oInput->required(true);
        $mValidator = new Validator($aPostData);
        $mValidator->rule('required', 'password');
        $oInput->validator($mValidator);
        $this->addElement($oInput);
    }

}
