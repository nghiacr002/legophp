<?php

namespace APP\Application\Module\Theme\Model;

use APP\Engine\Module\Model;
use APP\Application\Module\Theme\Model\DbTable\Widget as DbWidget;

class Widget extends Model
{

    public function __construct()
    {
        $this->_oTable = new DbWidget();
        parent::__construct();
    }

    public function getAllByArray($aConds = array(), $iPage = null, $iLimit = null, $sSelectFields = "*", $mOrder = null)
    {
        $aWidgets = parent::getAll($aConds, $iPage, $iLimit, $sSelectFields, $mOrder);
        $aResults = array();
        if (count($aWidgets))
        {
            foreach ($aWidgets as $iKey => $aWidget)
            {
                $aWidgetData = $aWidget->toArray();
                if ($aWidgetData['params'])
                {
                    $aWidgetData['params'] = json_decode($aWidgetData['params']);
                }
                $aWidgetData['widget_name'] = $this->app->language->translate($aWidgetData['widget_name']);
                $aResults[$aWidget->module_name][$aWidget->widget_router] = $aWidgetData;
            }
        }
        return $aResults;
    }
	public function getFromSession($sHash)
	{
		return $this->app->session->get('widget_tmp_'.$sHash);
	}
	public function saveToSession($sHash, $mValue)
	{
		return $this->app->session->set('widget_tmp_'.$sHash, $mValue);
	}
	public function cleanSession()
	{
		$this->app->session->cleanByPrefix("widget_tmp_");
	}
}
