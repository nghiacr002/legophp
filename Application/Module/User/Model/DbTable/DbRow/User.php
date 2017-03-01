<?php

namespace APP\Application\Module\User\Model\DbTable\DbRow;

use APP\Engine\Database\DbRow;

use APP\Engine\Image;
class User extends DbRow
{

    public function toArray($bBasicInfo = false)
    {
        $aData = $this->_aData;
        if ($bBasicInfo)
        {
            if (isset($aData['hash']))
            {
                unset($aData['hash']);
            }
            if (isset($aData['password']))
            {
                unset($aData['password']);
            }
        }
        return $aData;
    }
    public function user_image_url()
    {
    	return (new Image())->getThumbUrl("User/" . $this->user_image, 'small-size');
    }
}
