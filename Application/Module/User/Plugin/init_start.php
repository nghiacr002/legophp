<?php

use APP\Application\Module\User\Model\User;

function validate_user_profile_view($url)
{
    if (!empty($url))
    {
        $oUser = (new User())->getOne($url, 'user_name');
        if ($oUser && $oUser->user_id)
        {
            \APP\Engine\Application::getInstance()->request->setParam('aCurrentUserProfile', $oUser->toArray());
            return true;
        }
    }
    return false;
}
