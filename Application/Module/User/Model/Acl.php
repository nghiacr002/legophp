<?php

namespace APP\Application\Module\User\Model;

use APP\Application\Module\User\Model\User as UserModel;
use APP\Application\Module\User\Model\DbTable\DbRow\User as UserRow;

class Acl
{

    protected $_oUser;
    protected $_aPerms;

    public function __construct($mUser = null)
    {
        if ($mUser instanceof UserRow)
        {
            $this->_oUser = $mUser;
        } else
        {
            $this->_oUser = (new UserModel())->getOne($mUser);
        }
        $iUserGroupId = isset($this->_oUser->main_group_id) ? $this->_oUser->main_group_id : Group::GUEST;
        $this->_aPerms = (new Group())->getPerms($iUserGroupId);
    }

    public function getUser()
    {
        return $this->_oUser;
    }

    public function hasPerm($sPerm, $bThrowException = false)
    {
        $app = \APP\Engine\Application::getInstance();
        $bResult = false;
        if ($app->isDebug())
        {
            if (!isset($this->_aPerms[$sPerm]))
            {
                $bResult = (new Permission())->simpleAddPerm($sPerm);
                if ($bResult)
                {
                    $this->setPerm($sPerm, false);
                }
            }
        }
        if ($app->auth->getMethod()->isAdmin())
        {
            $bResult = true;
        } else
        {
            $bResult = isset($this->_aPerms[$sPerm]) ? $this->_aPerms[$sPerm] : false;
        }
        if (!$bResult && $bThrowException)
        {
            throw new AppException(
            $this->language()->translate('core.you_does_not_have_permission_to_access_this_area'), HTTP_CODE_FORBIDDEN);
        }
        return $bResult;
    }

    public function setPerm($sPerm, $mValue = true)
    {
        $this->_aPerms[$sPerm] = $mValue;
        return $this;
    }

    public function removePerm($sPerm)
    {
        if (isset($this->_aPerms[$sPerm]))
        {
            unset($this->_aPerms[$sPerm]);
            return true;
        }
        return false;
    }

}
