<?php

namespace APP\Application\Module\Core;

use APP\Engine\Module\Widget;

class SearchFilterWidget extends Widget
{

    public function process()
    {
		$aParams = $this->getParams();
		$sTitle = isset($aParams['title']) ? $aParams['title'] : "";
		$this->view->sTitle = $sTitle;
    }

}
