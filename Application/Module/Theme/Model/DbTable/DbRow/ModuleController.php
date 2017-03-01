<?php 
namespace APP\Application\Module\Theme\Model\DbTable\DbRow;
use APP\Application\Module\Theme\Model\Layout;
class ModuleController extends \APP\Engine\Database\DbRow
{
	public function buildContent($sControllerContent)
	{
		$oLayout = (new Layout())->getOne($this->layout_id);
		if(!$oLayout || !$oLayout->layout_id)
		{
			throw new \AppException("Template not found",HTTP_CODE_NOT_FOUND);
		}
		$oTpl = $this->app()->template;
		$sTemplateFileName = $oLayout->getLayoutFileName();
		$bIsHideHeader = (int)$this->hide_header_layout;
		$bIsHideFooter = (int)$this->hide_footer_layout;
    	$aAssignedParams = array(
    		'bIsHideHeader' => $bIsHideHeader,
    		'bIsHideFooter' => $bIsHideFooter,
    		'sPageContent' => $sControllerContent,
    	);
    	$this->app()->setSharedData('item-id', $this->controller_id);
    	$this->app()->setSharedData('item-type', "controller");
    	$this->app()->setSharedData('layout-id', $this->layout_id);
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
    	$sContentLayout = $oTpl->assign($aAssignedParams)->render($sTemplateFileName);
    	return $sContentLayout;
	}
}
