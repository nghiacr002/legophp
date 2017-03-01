<?php

namespace APP\Application\Module\Page;

use APP\Engine\Module\Widget;

class HtmlWidget extends Widget
{
    public function process()
    {
       $this->view->sTitle = $this->title;
       $this->view->sContent = $this->html_content;
    }
}
