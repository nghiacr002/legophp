<?php

namespace APP\Application\Module\Core;

use APP\Engine\Module\Widget;

class AdminMenuRightWidget extends Widget
{

    public function process()
    {
        $this->view->bIsAdminPanel = $this->app->isAdminPanel();
        $this->view->aCustomMenus = $aCustomMenus = $this->app->getSharedParam('custom-menu-header');
    }

}
