<?php

namespace APP\Application\Module\Theme\Model\DbTable\DbRow;

use APP\Engine\File;

class Layout extends \APP\Engine\Database\DbRow
{
	public function getLayoutFileName()
	{
		$sLayoutName = $this->layout_name;
		$sLayoutPagePathFile = APP_THEME_PATH . 'PageLayout' . APP_DS . $sLayoutName;
		if (!file_exists($sLayoutPagePathFile))
		{
			if(!empty($this->layout_content))
			{
				$sHashName = md5($this->layout_content);
				$sFileName = APP_PUBLIC_PATH . "Asset" . APP_DS . $sHashName."-".$sLayoutName;
				if(!file_exists($sFileName) || (defined('APP_NO_TEMPLATE') && APP_NO_TEMPLATE))
				{
					$oFile = new File();
					$oFile->write($sFileName,$this->layout_content);
				}
				$sLayoutPagePathFile = $sFileName;
			}
			else
			{
				$sLayoutPagePathFile = APP_THEME_PATH . 'PageLayout' . APP_DS . "Default.tpl";
			}
		}
		return $sLayoutPagePathFile;
	}
	public function buildContent($sControllerContent)
	{
		$oTpl = $this->app()->template;
		$sTemplateFileName = $this->getLayoutFileName();
		$bIsHideHeader = (int)!$this->header;
		$bIsHideFooter = (int)!$this->footer;
		$aAssignedParams = array(
			'bIsHideHeader' => $bIsHideHeader,
			'bIsHideFooter' => $bIsHideFooter,
			'sPageContent' => $sControllerContent,
		);
		$this->app()->setSharedData('item-id', $this->item_id);
		$this->app()->setSharedData('item-type', $this->item_type);
		$this->app()->setSharedData('layout-id', $this->layout_id);
		$sContentLayout = $oTpl->assign($aAssignedParams)->render($sTemplateFileName);
		if(!empty($this->custom_css))
		{
			$oTpl->setHeader(array(
				'<style>'.$this->custom_css.'</style>'
			));
		}
		if(!empty($this->custom_js))
		{
			$oTpl->setHeader(array(
					'<script>'.$this->custom_js.'</script>'
			));
		}
		return $sContentLayout;
	}
}
