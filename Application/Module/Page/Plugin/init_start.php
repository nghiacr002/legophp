<?php

use APP\Application\Module\Page\Model\Page;

function validate_page_view_detail($url)
{
    if (!empty($url))
    {
        $aPages = (new Page())->getAllWithCache();
        if (isset($aPages[$url]) && $aPages[$url]['page_status'] == Page::STATUS_ACTIVATED)
        {
            \APP\Engine\Application::getInstance()->request->setParam('aStaticPage', $aPages[$url]);
            return true;
        }
    }
    return false;
}
