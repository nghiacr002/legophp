<?php

namespace APP\Application\Module\User;
use APP\Engine\Module\Controller;
use APP\Application\Module\User\Model\User;
use APP\Application\Module\User\Form\AdminAddUser as AdminAddUserForm;

class ProfileController extends Controller
{
	public function ViewAction()
	{
		$aUser = $this->request()->get('aCurrentUserProfile');
		if(!isset($aUser['user_id']))
		{
			$this->url()->redirect('');
		}
		$this->view->user = (new User())->getOne($aUser['user_id']);
		//d($this->view->user);die();
		$oViewer = $this->auth()->getViewer();
		$this->view->viewer_id = ($oViewer) ? $oViewer->user_id : 0;
	}
	public function EditAction()
	{
		$oUserModel = (new User());
		$oViewer = $this->auth()->getViewer();
		if(!$oViewer)
		{
			$this->url()->redirect('user/login',array('url' => $this->url()->getCurrentUrl()),$this->language()->translate('user.you_must_login_first'));
		}
		$oFormAdminAddUser = new AdminAddUserForm();
		$this->view->oFormAdminAddUser = $oFormAdminAddUser;
		$oFormAdminAddUser->removeElement("password");
		$oFormAdminAddUser->removeElement("status");
		$oFormAdminAddUser->removeElement("main_group_id");

		$this->view->oExitUser = $oExitUser = $oUserModel->getOne($oViewer->user_id);
		if ($this->request()->isPost())
		{
			if ($oFormAdminAddUser->isValid())
			{
				try
				{

					$aData = $oFormAdminAddUser->getFormValues();
					$aData['user_id'] = $oViewer->user_id;
					$oExitUser->setData($aData);
					if ($oExitUser->isValid())
					{

						if ($oExitUser->update())
						{
							$this->app->flash->set($this->language()->translate('user.updated_user_successfully'));
							$this->url()->redirect('user/profile/edit');
						}
					} else
					{
						$aErrors = $oExitUser->getErrors();
						$this->flash()->set($aErrors, "system", 'error');
					}
				} catch (AppException $ex)
				{
					Logger::error($ex);
					$this->flash()->set($this->language()->translate('core.there_are_some_problems_with_system_please_try_again'), "system", 'error');
				}
			}
			else
			{
				$aMessages = $oFormAdminAddUser->getMessages();

				foreach ($aMessages as $sId => $sMessage)
				{
					$this->flash()->set($sMessage, $sId, 'error');
				}
			}
		}
		else
		{
			$oFormAdminAddUser->setFormValues($oViewer->getProps());
		}
		$this->view->sFormUrl = $this->url()->makeUrl('user/profile/edit');

	}
}