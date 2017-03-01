<?php

namespace APP\Application\Module\User\Model\DbTable;

use APP\Application\Module\User\Model\User as UserModel;

class User extends \APP\Engine\Database\DbTable
{

    protected $_sTableName = "user";
    protected $_mPrimaryKey = "user_id";
    protected $_aValidateRules = array(
        'required' => array(
            array('user_name'), array('email')
        ),
        'email' => 'email',
        'integer' => 'level',
    );
    protected $_sRowClass = "\\APP\\Application\\Module\User\Model\DbTable\\DbRow\\User";

    public function businessValidate(\APP\Engine\Database\DbRow $user)
    {
        $oUserModel = new UserModel();
        $oExistedUser = $oUserModel->getOne($user->email, 'email');
        if ($oExistedUser && $oExistedUser->user_id)
        {
            if ($user->user_id)
            {
                if ($user->email && $user->email == $oExistedUser->email && $user->user_id != $oExistedUser->user_id)
                {
                    $user->setError(\APP\Engine\Application::getInstance()->language->translate('user.user_has_been_existed'));
                    return false;
                }
            } else
            {
                if ($user->email && $user->email == $oExistedUser->email)
                {
                    $user->setError(\APP\Engine\Application::getInstance()->language->translate('user.user_has_been_existed'));
                    return false;
                }
            }
        }
        return true;
    }

}
