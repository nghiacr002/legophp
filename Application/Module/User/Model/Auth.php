<?php

namespace APP\Application\Module\User\Model;

use APP\Library\Auth\Method\Login;
use APP\Application\Module\User\Model\Group as UserGroupModel;
use APP\Application\Module\User\Model\User as UserModel;

class Auth extends Login
{

    private $_bIsLogin = false;
    private $_oUser = null;
    private $_sSessionAuthName = "aUser";
    private $_oAcl;

    public function __construct($oUser = null)
    {
        if (!$oUser)
        {
            $oSession = \APP\Engine\Application::getInstance()->session;
            $aUserLogin = $oSession->get($this->_sSessionAuthName);
            if (isset($aUserLogin['user_id']))
            {
                $oUser = (new UserModel())->getOne($aUserLogin['user_id']);
            } else
            {
                //check cookie
                $iUserId = \APP\Engine\Application::getInstance()->cookie->get('user_id', 0);
                $sUserHash = \APP\Engine\Application::getInstance()->cookie->get('user_hash', '');
                if (!empty($sUserHash) && $iUserId > 0)
                {
                    $oUser = (new UserModel())->getOne($iUserId);
                    if ($oUser && $oUser->user_id)
                    {
                        list($sHash, $sPassword) = $this->getHash($oUser->password, $oUser->hash);
                        if ($sPassword != $sUserHash)
                        {
                            unset($oUser);
                            $oUser = null;
                        }
                    }
                }
            }
        }
        $this->_oUser = $oUser;
        if ($oUser && $oUser->user_id)
        {
            $this->_bIsLogin = true;
        }
        $this->_oAcl = new Acl($this->_oUser);
    }

    public function getAcl()
    {
        return $this->_oAcl;
    }

    public function logout()
    {
        \APP\Engine\Application::getInstance()->session->remove($this->_sSessionAuthName);
        \APP\Engine\Application::getInstance()->cookie->remove('user_id');
        \APP\Engine\Application::getInstance()->cookie->remove('user_hash');
        return true;
    }

    public function login($sUserName, $sPassword, $sType = "email", $bNoPassword = false)
    {
        $oUser = (new User())->getOne($sUserName, $sType, false);
        $bIsLogin = false;
        if ($oUser && $oUser->status == UserModel::STATUS_ACTIVE)
        {
            $oSession = \APP\Engine\Application::getInstance()->session;
            if (!$bNoPassword)
            {
                list($sHash, $sPassword) = $this->getHash($sPassword, $oUser->hash);
                if ($sHash == $oUser->hash && $sPassword == $oUser->password)
                {
                    $bIsLogin = true;
                }
            } else
            {
                $bIsLogin = true;
            }
            if ($bIsLogin)
            {
                $aUserLogin = array(
                    'user_id' => $oUser->user_id,
                    'lasted_login' => APP_TIME,
                );
                $oSession->set($this->_sSessionAuthName, $aUserLogin);
                $oUser->lasted_login = APP_TIME;
                $oUser->update();
            }
            $this->_oUser = $oUser;
        }
        $this->_bIsLogin = $bIsLogin;
        return $bIsLogin;
    }

    public function getHash($sPassword, $sHash = "")
    {
        $sRandom = \APP\Engine\Application::getInstance()->getConfig('security', 'random');
        if (empty($sHash))
        {
            $sHash = md5(uniqid($sRandom, true));
            $sHash = substr($sHash, 0, 5);
        }
        return array($sHash, sha1($sRandom . $sPassword . $sHash));
    }

    public function isAdmin()
    {
        if (!$this->isLogin() || !$this->_oUser)
        {
            return false;
        }
        if ($this->_oUser->main_group_id != UserGroupModel::ADMINISTRATOR)
        {
            return false;
        }
        return true;
    }

    public function isLogin()
    {
        return $this->_bIsLogin;
    }

    public function getCurrentUser()
    {
        return $this->_oUser;
    }

    public function getViewer()
    {
        return $this->getCurrentUser();
    }

    public function isAuthenticated()
    {
        if (!$this->_oUser)
        {
            return false;
        }
        return $this->isLogin();
    }

    public function unauthorized()
    {
        return $this->logout();
    }

}
