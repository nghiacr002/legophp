<?php

namespace APP\Application\Module\User;

use APP\Engine\Module\Widget;

class HeaderMenuWidget extends Widget
{
    public function process()
    {
        if ($this->auth())
        {
            $this->view->currentUser = $this->auth()->getViewer();
        }
        $this->view->bIsAdminPanel = $this->app->isAdminPanel();
        
    }

}
