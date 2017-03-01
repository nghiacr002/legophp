<?php

namespace APP\Application\Module\Theme;

use APP\Engine\Module\Widget;

class HeaderWidget extends Widget
{

    public function process()
    {
        $this->view->sSiteName = $this->app->getSetting('core.site_name');
    }

}
