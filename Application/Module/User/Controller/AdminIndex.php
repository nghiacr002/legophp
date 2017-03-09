<?php

namespace APP\Application\Module\User;

use APP\Engine\Module\Controller;
use APP\Application\Module\User\Model\User as UserModel;
use APP\Application\Module\User\Form\AdminAddUser as AdminAddUserForm;
use APP\Engine\Logger;
use APP\Engine\AppException;
use APP\Application\Module\User\Model\Auth as UserAuth;
use APP\Engine\HTML\Pagination;
use APP\Engine\HTML\Filter;
use APP\Application\Module\User\Model\Group;

class AdminIndexController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->auth()->acl()->hasPerm('core.can_access_module_user', true);
    }

    public function IndexAction()
    {

    }

    public function DeleteAction()
    {
        $this->auth()->acl()->hasPerm('core.can_delete_user', true);
        $oUserModel = new UserModel();
        $iUserId = $this->request()->get('id');
        $oExitUser = $oUserModel->getOne($iUserId);
        if (!$oExitUser || !$oExitUser->user_id)
        {
            $this->app->flash->set($this->language()->translate('user.user_does_not_exist'));
            $this->url()->redirect('user/browse', array('admincp' => true));
        }
        if ($oExitUser->delete())
        {
            $this->app->flash->set($this->language()->translate('user.deleted_user_successfully'));
            $this->url()->redirect('user/browse', array('admincp' => true));
        } else
        {
            throw new AppException($this->language()->translate('user.cannot_delete_this_user'), HTTP_CODE_BAD_REQUEST);
        }
    }

    public function EditAction()
    {
        $this->auth()->acl()->hasPerm('core.can_edit_user', true);

        $oUserModel = new UserModel();
        $iUserId = $this->request()->get('id');
        $this->view->oExitUser = $oExitUser = $oUserModel->getOne($iUserId);
        if (!$oExitUser || !$oExitUser->user_id)
        {
            $this->app->flash->set($this->language()->translate('user.user_does_not_exist'));
            $this->url()->redirect('user/browse', array('admincp' => true));
        }

        $oFormAdminAddUser = new AdminAddUserForm();
        $this->view->oFormAdminAddUser = $oFormAdminAddUser;
        if ($this->request()->isPost())
        {
            if ($oFormAdminAddUser->isValid())
            {
                try
                {
                    $oUserAuth = new UserAuth();
                    $aData = $oFormAdminAddUser->getFormValues();

                    $aData['user_id'] = $iUserId;
                    $oExitUser->setData($aData);
                    if (isset($aData['password']))
                    {
                        $oUserAuth = new UserAuth();
                        list($sHash, $sHashedPassword) = $oUserAuth->getHash($aData['password']);
                        $oExitUser->password = $sHashedPassword;
                        $oExitUser->hash = $sHash;
                    }
                    if ($oExitUser->isValid())
                    {
                        if ($oExitUser->update())
                        {
                            $this->app->flash->set($this->language()->translate('user.updated_user_successfully'));
                            $this->url()->redirect('user/edit', array('id' => $iUserId, 'admincp' => true));
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
        } else
        {
            $oFormAdminAddUser->setFormValues($oExitUser->getProps());
        }
        $this->template()->setHeader(array(
            'jquery-ui.min.js' => 'module_core',
            'jquery-ui.min.css' => 'module_core',
            'bootstrap-switch.min.js' => 'module_core',
            'bootstrap-switch.min.css' => 'module_core',
            'admin_user.js' => 'module_user',
            'admin_user.css' => 'module_user',
        ));
		$this->view->bIsEdit = true;
        $sUrl = $this->url()->makeUrl('user/browse', array('admincp' => true));
        $aBreadCrumb = array(
            'title' => $this->language()->translate('user.user'),
            'icon' => '',
            'url' => 'javascript:void(0);',
            'title_extra' => '',
            'path' => array(
                $sUrl => $this->language()->translate('user.browse'),
            ),
        );
        $this->view->sFormUrl = $this->url()->makeUrl('user/edit', array('id' => $iUserId, 'admincp' => true));
        $this->template()->setBreadCrumb($aBreadCrumb);
        $this->view->setLayout('Add.tpl');
        $this->setActiveMenu('user/browse/index');
    }

    public function AddAction()
    {
        $this->auth()->acl()->hasPerm('core.can_add_user', true);
        $oFormAdminAddUser = new AdminAddUserForm();
        $this->view->oFormAdminAddUser = $oFormAdminAddUser;
        if ($this->request()->isPost())
        {
            if ($oFormAdminAddUser->isValid())
            {
                try
                {
                    $oUserAuth = new UserAuth();
                    $aData = $oFormAdminAddUser->getFormValues();
                    $oUserModel = new UserModel();
                    $oNewUser = $oUserModel->getTable()->createRow($aData);
                    list($sHash, $sHashedPassword) = $oUserAuth->getHash($oNewUser->password);
                    $oNewUser->password = $sHashedPassword;
                    $oNewUser->hash = $sHash;
                    $oNewUser->joined_day = APP_TIME;
                    if ($oNewUser->isValid())
                    {
                        $iUserId = $oNewUser->save();
                        if ($iUserId)
                        {
                            $this->app->flash->set($this->language()->translate('core.registered_membership_successfully'));
                            $this->url()->redirect('user/edit', array('id' => $iUserId, 'admincp' => true));
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
                $aMessages = $oFormAdminAddUser->getMessages();
                foreach ($aMessages as $sId => $sMessage)
                {
                    $this->flash()->set($sMessage, $sId, 'error');
                }
            }
        }
        $this->template()->setHeader(array(
            'jquery-ui.min.js' => 'module_core',
            'jquery-ui.min.css' => 'module_core',
            'bootstrap-switch.min.js' => 'module_core',
            'bootstrap-switch.min.css' => 'module_core',
            'admin_user.js' => 'module_user',
            'admin_user.css' => 'module_user',
        ));

        $sUrl = $this->url()->makeUrl('user/browse', array('admincp' => true));
        $aBreadCrumb = array(
            'title' => $this->language()->translate('user.user'),
            'icon' => '',
            'url' => 'javascript:void(0);',
            'title_extra' => '',
            'path' => array(
                $sUrl => $this->language()->translate('user.browse'),
            ),
        );
        $this->view->sFormUrl = $this->url()->makeUrl('user/add', array('admincp' => true));
        $this->template()->setBreadCrumb($aBreadCrumb);
    }

    public function BrowseAction()
    {
        $oUserModel = (new UserModel());
        $iLimit = $this->request()->get('limit', 20);
        $iCurrentPage = $this->request()->get('page', 1);
        $this->view->oFilter = $oFilter = new Filter();
        $aGroups = (new Group())->getAll();
        $aOptions = array();
        $aOptions[] = array(
            'value' => '',
            'name' => $this->language()->translate('core.all')
        );
        foreach ($aGroups as $ikey => $oGroup)
        {
            $aOptions[] = array(
                'name' => $oGroup->group_name,
                'value' => $oGroup->user_group_id
            );
        }
        $oFilter->setParams(array(
            'full-name' => array(
                'type' => 'text',
                'name' => 'full_name',
                'placeholder' => $this->language()->translate('user.enter_full_name_to_search')
            ),
            'email' => array(
                'type' => 'text',
                'name' => 'email',
                'placeholder' => $this->language()->translate('user.enter_email_to_search')
            ),
            'main-group' => array(
                'type' => 'select',
                'name' => 'main_group_id',
                'options' => $aOptions
            ),
            'search-button' => array(
                'type' => 'search',
                'class' => 'btn btn-success',
                'value' => $this->language()->translate('user.search')
            )
        ));
        $aFilter = $oFilter->getFilterValues();
        $aConds = array();
        if (isset($aFilter) && count($aFilter))
        {
            foreach ($aFilter as $iKey => $mValue)
            {
                $aConds[] = array($iKey, '%' . $mValue . '%', 'LIKE');
            }
        }

        $iTotal = $oUserModel->getTotal($aConds);
        $aUsers = $oUserModel->getAll($aConds, $iCurrentPage, $iLimit, '*', array('lasted_login', 'DESC'));
        $this->view->iTotalUser = $iTotal;
        $this->view->aUsers = $aUsers;
        $sUrl = $this->url()->makeUrl('user/browse', array('admincp' => true));
        $aBreadCrumb = array(
            'title' => $this->language()->translate('user.browse'),
            'icon' => '',
            'url' => 'javascript:void(0);',
            'title_extra' => '',
            'path' => array(
                $sUrl => $this->language()->translate('user.browse'),
            ),
        );
        $this->template()->setBreadCrumb($aBreadCrumb);
        $aParams = array(
            'router' => 'user/browse',
            'params' => array('admincp' => true, 'limit' => $iLimit),
            'total' => $iTotal,
            'current' => $iCurrentPage,
            'limit' => $iLimit,
        );
        if (count($aFilter))
        {
            $aParams['params'] = array_merge($aParams['params'], $aFilter);
        }
        $this->view->paginator = new Pagination($aParams);
    }

}
