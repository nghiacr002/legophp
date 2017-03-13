<?php

namespace APP\Application\Module\User;

use APP\Engine\Module\Controller;
use APP\Application\Module\User\Model\Group as UserGroupModel;
use APP\Application\Module\User\Model\User as UserModel;
use APP\Application\Module\User\Form\Login as LoginForm;
use APP\Application\Module\User\Form\Register as RegisterForm;
use APP\Engine\Logger;
use APP\Engine\AppException;
use APP\Application\Module\User\Model\Auth as UserAuth;
use APP\Application\Module\User\Model\RequestToken;
use APP\Application\Module\User\Model\DbTable\DbRow\RequestToken as RowRequestToken;

class IndexController extends Controller
{

    public function IndexAction()
    {
        $this->view->abcd = "nice to meet you";
    }
    public function ChangepasswordAction()
    {
    	$request =  $this->request();
    	if ($this->auth()->isAuthenticated())
    	{
    		$this->router()->url()->redirect('');
    	}
    	$sToken = $request->get('token');
    	if(empty($sToken))
    	{
    		$this->router()->url()->redirect('');
    	}
    	$oUserRequestToken = (new RequestToken())->getOne(array($sToken,'lost_password'),array('code','request_type'));
    	if(!$oUserRequestToken || $oUserRequestToken->status != RowRequestToken::STATUS_CREATED)
    	{
    		$this->router()->url()->redirect('');
    	}
    	$aParams = $oUserRequestToken->params;
    	$sEmail = isset($aParams['email']) ? $aParams['email']: "";
    	$oExistedUser = (new UserModel())->getOne($sEmail,'email');
    	if(!$oExistedUser)
    	{
    		$this->router()->url()->redirect('');
    	}
		$this->view->sRequestToken = $sToken;
		if($this->request()->isPost())
		{
			$sPassword = $request->get('password');
			$sRepeatPassword = $request->get('repeat_password');
			$sError = "";
			if(empty($sPassword) || empty($sRepeatPassword))
			{
				$sError = $this->flash()->set($this->language()->translate('user.password_cannot_be_empty'));
			}
			elseif($sPassword != $sRepeatPassword)
			{
				$sError = $this->flash()->set($this->language()->translate('user.password_is_not_match'));
			}
			if(empty($sError))
			{
				list($sHash, $sNewPassHash ) = (new UserAuth())->getHash($sPassword);
				$oExistedUser->hash = $sHash;
				$oExistedUser->password = $sNewPassHash;
				if($oExistedUser->isValid())
				{
					$oExistedUser->update();
					$oUserRequestToken->verified_time = APP_TIME;
					$oUserRequestToken->status = RowRequestToken::STATUS_PROCESSED;
					$oUserRequestToken->user_id = $oExistedUser->user_id;
					$oUserRequestToken->update();
				}

				$this->url()->redirect('user/login',array(), $this->language()->translate('user.your_password_has_been_change_pls_login_with_new_one'));
			}
		}
    }
    public function ForgotAction()
    {
    	if ($this->auth()->isAuthenticated())
    	{
    		$this->router()->url()->redirect('');
    	}
		if($this->request()->isPost())
		{
			$sEmail = $this->request()->get('email');

			if($sEmail)
			{
				$oExistedUser = (new UserModel())->getOne($sEmail,'email');
				if($oExistedUser && $oExistedUser->user_id)
				{
					//not implement now
					(new UserAuth())->forgotPassword($sEmail);
				}
			}
			$this->url()->redirect('user/forgot',array(),$this->language()->translate('user.email_sent_out_pls_check_your_mail_box'));
		}
    }

    public function LogoutAction()
    {
        if (!$this->auth()->isAuthenticated())
        {
            $this->router()->url()->redirect('');
        }
        $this->auth()->unauthorized();
        $this->router()->url()->redirect('');
    }

    public function LoginAction()
    {
        if ($this->auth()->isAuthenticated())
        {
            $this->router()->url()->redirect('');
        }
        $sUrl = $this->request()->get('url');
        $oLoginForm = new LoginForm();
        if ($this->request()->isPost())
        {
            if ($oLoginForm->isValid())
            {
                $oUserAuth = new UserAuth();
                $aData = $oLoginForm->getFormValues();
                if ($oUserAuth->login($aData['email'], $aData['password'], "email"))
                {
                    if ($this->request()->get('remember'))
                    {
                        (new UserModel())->remember($oUserAuth->getCurrentUser()->user_id);
                    }
                    if (empty($sUrl))
                    {
                        $sUrl = $this->app->getBaseURL();
                    }
                    $this->url()->redirect($sUrl);
                } else
                {
                    $this->flash()->set($this->language()->translate('core.login_fail'), 'system', 'error');
                    $this->url()->redirect('user/login');
                }
            } else
            {
                $aMessages = $oLoginForm->getMessages();
                foreach ($aMessages as $sId => $sMessage)
                {
                    $this->flash()->set($sMessage, $sId, 'error');
                }
            }
        }
        $this->view->sRedirectUrl = $sUrl;
    }

    public function RegisterAction()
    {
        if ($this->auth()->isAuthenticated())
        {
            $this->router()->url()->redirect('');
        }
        $bIsModeInstall = ($this->request()->get('mode') == "install") ? true: false;
        if(!$this->app()->getSetting('user.enable_register_account') && !$bIsModeInstall)
        {
        	$this->router()->url()->redirect('');
        }
        $oUserModel = new UserModel();

        if($bIsModeInstall)
        {
			$oExistedAdmin = $oUserModel->getOne(UserGroupModel::ADMINISTRATOR,'main_group_id');
			if($oExistedAdmin && $oExistedAdmin->user_id)
			{
				$this->router()->url()->redirect('user/register');
			}
			$this->flash()->set($this->language()->translate('core.register_first_account_as_admin_system'));
        }
        $oRegisterForm = new RegisterForm();
        $oRegisterForm->setId("registerForm");
        if ($this->request()->isPost())
        {
            if ($oRegisterForm->isValid())
            {
                try
                {
                    $oUserAuth = new UserAuth();
                    $aData = $oRegisterForm->getFormValues();
                    unset($aData['repeatpassword']);
                    unset($aData['termofuse']);

                    $oNewUser = $oUserModel->getTable()->createRow($aData);
                    list($sHash, $sHashedPassword) = $oUserAuth->getHash($oNewUser->password);
                    $oNewUser->password = $sHashedPassword;
                    $oNewUser->hash = $sHash;
                    $oNewUser->joined_day = APP_TIME;
                    if ($oNewUser->isValid())
                    {
                    	if($bIsModeInstall)
                    	{
                    		$oNewUser->main_group_id = UserGroupModel::ADMINISTRATOR;
                    		$oNewUser->status = UserModel::STATUS_ACTIVE;
                    	}
                    	if($this->app()->getSetting('user.auto_approve_user_when_register'))
                    	{
                    		$oNewUser->status = UserModel::STATUS_ACTIVE;
                    	}
                        $iUserId = $oNewUser->save();
                        if ($iUserId)
                        {
                            if ($oUserAuth->login($oNewUser->email, $aData['password'], "email"))
                            {
                            	if(!$bIsModeInstall)
                            	{
                            		$this->app->flash->set($this->language()->translate('core.registered_membership_successfully'));
                            	}
                            	else
                            	{
                            		$this->app->flash->set($this->language()->translate('core.welcome_simplecms_nice_day'));
                            	}

                                $this->url()->redirect($this->app->getBaseURL());
                            }
                            else
                            {
                            	$this->app->flash->set($this->language()->translate('user.your_account_has_been_created'));
                            }
                        }
                    } else
                    {
                        $aErrors = $oNewUser->getErrors();
                        $this->flash()->set($aErrors, "system", 'error');
                    }
                } catch (AppException $ex)
                {
                    Logger::error($ex);
                    $this->flash()->set($this->language()->translate('core.there_are_some_problems_with_system_please_try_again'), "system", 'error');
                }
            } else
            {
                $aMessages = $oRegisterForm->getMessages();
                foreach ($aMessages as $sId => $sMessage)
                {
                    $this->flash()->set($sMessage, $sId, 'error');
                }
            }
        }
        $this->view->registerForm = $oRegisterForm;
        $this->view->bIsModeInstall = $bIsModeInstall;
        $this->template()->setHeader(array(
        	'register.js' => 'module_user'
        ));
    }

}
