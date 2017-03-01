<?php

namespace APP\Application\Module\Theme;

use APP\Engine\Module\Widget;
use APP\Application\Module\Theme\Model\LayoutWidgets;
use APP\Application\Module\Theme\Model\LayoutDesign;

class LocationWidget extends Widget
{

    public function __construct()
    {
        parent::__construct();
    }

    public function process()
    {
		$bIsDesignLayout = $this->app()->getSharedParam('design-layout');
		$this->view->iLocationId = $iLocationId = $this->location_id;
		$this->view->bDesignMode = $bDesignMode = $this->bDesignMode;
		$iLayoutId = $this->app()->getSharedParam('layout-id');
		if($bIsDesignLayout)
		{
			$aLayoutWidgets = (new LayoutDesign())->getByLocation($iLocationId, $iLayoutId);
		}
		else
		{
			$this->view->iItemId = $iItemId = $this->app->getSharedParam('item-id');
			$this->view->sItemType = $sItemType = $this->app->getSharedParam('item-type');
			$oLayoutWidgets = (new LayoutWidgets())->setItem($iItemId, $sItemType);
			$aLayoutWidgets = $oLayoutWidgets->getByLocation($iLocationId, $iLayoutId);
			if(!count($aLayoutWidgets))
			{
				$aLayoutWidgets = (new LayoutDesign())->getByLocation($iLocationId, $iLayoutId);
			}
		}
		$this->view->aLayoutWidgets = $aLayoutWidgets;
    }

}
