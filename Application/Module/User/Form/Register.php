<?php

namespace APP\Application\Module\User\Form;

use APP\Engine\HTML\Form as HtmlForm;
use APP\Engine\Validator;
use APP\Engine\HTML\Input;
use APP\Engine\HTML\Checkbox;
use APP\Application\Module\User\Model\User;

class Register extends HtmlForm
{

    public function __construct()
    {
        parent::__construct();
        $this->_aElements = array();
        $aPostData = $this->_aData;
        $oInput = new Input();
        $oInput->setName("full_name");
        $oInput->setValue($this->app->request->get('full_name'));
        $oInput->required(true);
        $oInput->setErrorMessage($this->app->language->translate('core.fullname_cannot_be_empty'));
        $this->addElement($oInput);

        $oInput = new Input();
        $oInput->setName("user_name");
        $oInput->setValue($this->app->request->get('user_name'));
        $oInput->required(true);
        $oInput->setErrorMessage($this->app->language->translate('user.user_name_cannot_be_empty'));

        $this->addElement($oInput);
        $oInput = new Input();
        $oInput->setName("email");
        $oInput->setValue($this->app->request->get('email'));
        $oInput->required(true);
        $mValidator = new Validator($aPostData);
        $mValidator->rule('email', 'email');
        $oInput->validator($mValidator);
        $this->addElement($oInput);

        $oInput = new Input();
        $oInput->setName("password");
        $oInput->setValue($this->app->request->get('password'));
        $oInput->required(true);
        $this->addElement($oInput);

        $oInput = new Input();
        $oInput->setName("repeatpassword");
        $oInput->setValue($this->app->request->get('repeatpassword'));
        $oInput->required(true);
        $mValidator = new Validator($aPostData);
        $mValidator->rule('equals', 'repeatpassword', 'password');
        $oInput->setErrorMessage($this->app->language->translate('core.password_is_not_matched'));
        $oInput->validator($mValidator);
        $this->addElement($oInput);

        $oInput = new Checkbox();
        $oInput->setName("termofuse");
        $oInput->setValue($this->app->request->get('termofuse'));
        $oInput->required(true);
        $oInput->setErrorMessage($this->app->language->translate('core.please_read_and_accept_our_term_of_use'));
        $this->addElement($oInput);
    }

}
