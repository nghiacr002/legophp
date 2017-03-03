<?php

namespace APP\Application\Module\Core;

use APP\Engine\Module\Widget;

class SliderWidget extends Widget
{
    public function process()
    {
		$aParams = $this->getParams();
		$sTitle = isset($aParams['title']) ? $aParams['title']: $this->language()->get('core.slider');
		$this->setTitle($sTitle);
		$aImages = isset($aParams['images']) ? $aParams['images']: array();
		if(!count($aImages))
		{
			return false;
		}
		$this->view->aSliderImages = $aImages;
		$this->view->widgetId = $this->getId(). uniqid(APP_TIME). APP_TIME;
    }
}
