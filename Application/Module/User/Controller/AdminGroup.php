<?php

namespace APP\Application\Module\User;

use APP\Engine\Module\Controller;
use APP\Application\Module\User\Model\Group as UserGroupModel;
use APP\Application\Module\User\Model\Permission as PermissionModel;
use APP\Application\Module\User\Model\Group as GroupModel;
use APP\Application\Module\User\Model\DbTable\GroupPermission;
use APP\Engine\AppException;
use APP\Engine\Logger;

class AdminGroupController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->auth()->acl()->hasPerm('core.can_access_group_page', true);
    }

    public function IndexAction()
    {
        $aGroups = (new UserGroupModel())->getAll();
        $this->view->aGroups = $aGroups;
        $this->template()->setHeader(array(
            'bootstrap-switch.min.js' => 'module_core',
            'bootstrap-switch.min.css' => 'module_core',
            'admin_group.js' => 'module_user',
        ));
        $sUrl = $this->url()->makeUrl('user/group', array('admincp' => true));
        $aBreadCrumb = array(
            'title' => $this->language()->translate('user.manage_group'),
            'icon' => '',
            'url' => 'javascript:void(0);',
            'title_extra' => '',
            'path' => array(
                $sUrl => $this->language()->translate('user.manage_group'),
            ),
        );
        $this->template()->setBreadCrumb($aBreadCrumb);
    }

    public function EditAction()
    {
        $iId = $this->request()->get('id');
        $oGroupModel = (new GroupModel());
        $oExistedGroup = $oGroupModel->getForEdit($iId);
        if (!$oExistedGroup->user_group_id)
        {
            $this->url()->redirect('user/group/add', array('admincp' => true));
        }
        if ($this->request()->isPost())
        {
            $aData = $this->request()->getParams();
            $aData['is_active'] = isset($aData['is_active']) ? $aData['is_active'] : 0;
            $aPerms = array();
            if (isset($aData['perms']))
            {
                $aPerms = $aData['perms'];
                unset($aData['perms']);
                unset($aData['id']);
            }
            $oExistedGroup->mapData($aData)->unsetData('perms');
            $mResult = ($oExistedGroup->update());
            $oExistedGroup->emptyPerms();
            if (count($aPerms))
            {
                foreach ($aPerms as $iPermId => $mValue)
                {
                    try
                    {
                        $oNewGroupPermission = (new GroupPermission())->createRow(array(
                            'user_group_id' => $oExistedGroup->user_group_id,
                            'permission_id' => $iPermId,
                            'gp_value' => $mValue,
                        ));
                        $oNewGroupPermission->save();
                    } catch (AppException $ex)
                    {
                        Logger::error($ex);
                    }
                }
            }
            $this->url()->redirect('user/group/edit', array('admincp' => true, "id" => $oExistedGroup->user_group_id), $this->language()->translate('user.updated_group_successfully'));
        }

        $aConds = array(
            array(
                "parent_group_id", 0
            )
        );
        $this->view->aGroups = $aGroups = (new UserGroupModel())->getAll($aConds);
        $aPerms = (new PermissionModel())->getAll();
        if (is_array($oExistedGroup->perms) && count($oExistedGroup->perms))
        {
            foreach ($aPerms as $iKey => $oPerm)
            {
                foreach ($oExistedGroup->perms as $sName => $aPerm)
                {
                    if ($aPerm['permission_id'] == $oPerm->permission_id)
                    {
                        $oPerm->gp_value = $aPerm['gp_value'];
                        break;
                    }
                }
            }
        }

        $this->view->oExistedGroup = $oExistedGroup;
        $this->view->aPerms = $aPerms;
        $this->template()->setHeader(array(
            'bootstrap-switch.min.js' => 'module_core',
            'bootstrap-switch.min.css' => 'module_core',
            'admin_group.js' => 'module_user',
            'admin_group.css' => 'module_user',
        ));
        $this->view->setLayout('Add');
        $sUrl = $this->url()->makeUrl('user/group', array('admincp' => true));
        $aBreadCrumb = array(
            'title' => $this->language()->translate('user.manage_group'),
            'icon' => '',
            'url' => 'javascript:void(0);',
            'title_extra' => '',
            'path' => array(
                $sUrl => $this->language()->translate('user.manage_group'),
            ),
        );
        $this->template()->setBreadCrumb($aBreadCrumb);
    }
    public function DeleteAction()
    {
    	$this->auth()->acl()->hasPerm('core.can_delete_user_group', true);
    	$oGroupModel = (new GroupModel());
    	$iUserGroupId = $this->request()->get('id');
    	$oExistedGroup = $oGroupModel->getOne($iUserGroupId);
    	if (!$oExistedGroup || !$oExistedGroup->user_group_id)
    	{
    		$this->app->flash->set($this->language()->translate('user.group_does_not_exist'));
    		$this->url()->redirect('user/group', array('admincp' => true));
    	}
    	if ($oExistedGroup->delete())
    	{
    		$this->app->flash->set($this->language()->translate('user.deleted_group_successfully'));
    		$this->url()->redirect('user/group', array('admincp' => true));
    	} else
    	{
    		throw new AppException($this->language()->translate('user.cannot_delete_this_group'), HTTP_CODE_BAD_REQUEST);
    	}
    }

    public function AddAction()
    {
        if ($this->request()->isPost())
        {
            $oGroupModel = (new GroupModel());
            $aData = $this->request()->getParams();
            $aPerms = array();
            if (isset($aData['perms']))
            {
                $aPerms = $aData['perms'];
                unset($aData['perms']);
            }
            $oNewGroup = $oGroupModel->getTable()->createRow($aData);
            if ($oNewGroup->isValid())
            {
                if (( $iGroupId = $oNewGroup->save()))
                {
                    if (count($aPerms))
                    {
                        foreach ($aPerms as $iPermId => $mValue)
                        {
                            try
                            {
                                $oNewGroupPermission = (new GroupPermission())->createRow(array(
                                    'user_group_id' => $iGroupId,
                                    'permission_id' => $iPermId,
                                    'gp_value' => $mValue,
                                ));
                                $oNewGroupPermission->save();
                            } catch (AppException $ex)
                            {
                                Logger::error($ex);
                            }
                        }
                    }
                    $this->url()->redirect('user/group', array('admincp' => true, "id" => $iGroupId), $this->language()->translate('user.added_new_group_successfully'));
                }
            } else
            {
                $aErrors = $oNewGroup->getErrors();

                foreach ($aErrors as $sId => $sMessage)
                {
                    $this->flash()->set($sMessage, $sId, 'error');
                }
            }
        }
        $aConds = array(
            array(
                "parent_group_id", 0
            )
        );
        $this->view->aGroups = $aGroups = (new UserGroupModel())->getAll($aConds);
        $this->view->aPerms = $aPerms = (new PermissionModel())->getAll();

        $this->template()->setHeader(array(
            'bootstrap-switch.min.js' => 'module_core',
            'bootstrap-switch.min.css' => 'module_core',
            'admin_group.js' => 'module_user',
            'admin_group.css' => 'module_user',
        ));
        $sUrl = $this->url()->makeUrl('user/group', array('admincp' => true));
        $aBreadCrumb = array(
            'title' => $this->language()->translate('user.manage_group'),
            'icon' => '',
            'url' => 'javascript:void(0);',
            'title_extra' => '',
            'path' => array(
                $sUrl => $this->language()->translate('user.manage_group'),
            ),
        );
        $this->template()->setBreadCrumb($aBreadCrumb);
    }

}
