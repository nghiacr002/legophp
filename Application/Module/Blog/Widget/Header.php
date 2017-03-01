<?php

namespace APP\Application\Module\Theme;

use APP\Engine\Module\Widget;

class HeaderWidget extends Widget
{

    public function process()
    {
        $this->view->title = "Core Title";
        $this->view->content = "Core Content";
    }

}
