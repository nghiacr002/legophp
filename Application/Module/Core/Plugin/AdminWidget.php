<?php
class AdminWidget
{
	public function getWidgetOptionTemplate($aWidgetOptions)
	{
		$sTemplateName = $aWidgetOptions['params_template'];
		$sPathFile = APP_MODULE_PATH. "Core". APP_DS . "View" . APP_DS . "options_widget_".strtolower($sTemplateName);
		$sPathFile = \APP\Engine\Template::getFileName($sPathFile);
		return $sPathFile;
	}
}