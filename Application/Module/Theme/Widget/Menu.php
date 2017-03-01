<?php

namespace APP\Application\Module\Theme;

use APP\Engine\Module\Widget;
use APP\Application\Module\Theme\Model\Menu as MenuModel;

class MenuWidget extends Widget
{

    public function __construct()
    {
        parent::__construct();
    }

    public function process()
    {
        $this->view->bIsAdminPanel = $bIsAdminPanel = $this->app->isAdminPanel();
        if ($bIsAdminPanel)
        {
            $aMenus = (new MenuModel())->getAdminMenus();
        }
        else
        {

            $aMenus = (new MenuModel())->getMenusByType('main_menu');
        }
        $this->view->aSystemMenus = $aMenus;
    }

}
