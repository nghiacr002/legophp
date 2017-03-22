<?php

namespace APP\Application\Module\Theme;

use APP\Engine\Module\Widget;

class BreadcrumbWidget extends Widget
{
    public function process()
    {
        $this->view->bIsAdminCP = $bIsAdminCP = $this->app()->isAdminPanel();
        $this->view->sHomeURL = $sHomeURL = $this->url()->makeUrl('',array('admincp' => $bIsAdminCP));
    }

}
