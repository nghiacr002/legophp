<?php

namespace APP\Application\Module\Theme;

use APP\Engine\Module\Controller;
use APP\Application\Module\Theme\Model\Widget as WidgetModel;
use APP\Application\Module\Theme\Model\Layout;
use APP\Application\Module\Theme\Model\LayoutDesign;
use APP\Engine\File;
use APP\Application\Module\Theme\Model\LayoutWidgets;
use APP\Application\Module\Theme\Model\ModuleController;
use APP\Application\Module\Page\Model\Page as PageModel;

class AdminLayoutController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->auth()->acl()->hasPerm('core.can_access_layout_page', true);
    }
    public function AddAction()
    {
    	$oFile = new File();
    	$oLayout = new Layout();
    	$sLayoutPath = $oLayout->getLayoutPath();
    	$this->view->sLayoutPath = $sLayoutPath;
    	if($this->request()->isPost())
    	{
    		$aParams = $this->request()->getParams();
    		$aInsert = array(
    			'layout_name' => isset($aParams['layout_name']) ? $aParams['layout_name'] : "layout-design-".APP_TIME.'.tpl',
    			'layout_content' => isset($aParams['layout_content']) ? $aParams['layout_content'] : "",
    			'layout_title' => isset($aParams['layout_title']) ? $aParams['layout_title'] : "",
    			'header' => isset($aParams['header']) ? (int)$aParams['header'] : 0,
    			'footer' => isset($aParams['footer']) ? (int)$aParams['footer'] : 0,
    		);
    		$aInsert['header'] = (int)!$aInsert['header'];
    		$aInsert['footer'] = (int)!$aInsert['footer'];
    		$oNewLayout = $oLayout->getTable()->createRow($aInsert);
    		if($oNewLayout->isValid())
    		{
    			if($iId = $oNewLayout->save())
    			{
    				$this->url()->redirect('theme/layout/edit',array('admincp' => true, 'id' => $iId),
    							$this->language()->translate('theme.created_new_layout_successfully'));
    			}
    		}
    	}
    	$this->template()->setHeader(array(
    		'jquery-ui.min.js' => 'module_core',
    		'jquery-ui.min.css' => 'module_core',
    		'jquery.mjs.nestedSortable.js' => 'module_core',
    		'admin_design.js' => 'module_theme',
    		'theme_layout.css' => 'module_theme',
    		'theme_design.css' => 'module_theme',
    	));
    	$this->view->bIsHideHeader = $bIsHideHeader = 0;
    	$this->view->bIsHideFooter = $bIsHideFooter = 0;
    	$this->view->sLayoutPagePathFile = $sLayoutPath. "Skeleton.tpl";
    	$this->view->bIsDesignLayout = true;
    	$aBreadCrumb = array(
    			'title' => $this->language()->translate('theme.new_layout'),
    			'extra_title' => '',
    			'icon' => '',
    			'url' => 'javascript:void(0);',
    			'title_extra' => '',
    			'path' => array(
    					$this->url()->makeUrl('theme/layouts', array('admincp' => true)) => $this->language()->translate('theme.layouts'),
    			),
    	);
    	$this->template()->setBreadCrumb($aBreadCrumb);
    }
	public function IndexAction()
	{
		$oLayoutModel = (new Layout());
		$aLayouts = $oLayoutModel->getAll();
		$this->template()->setHeader(array(
			'bootstrap-switch.min.js' => 'module_core',
			'bootstrap-switch.min.css' => 'module_core',
			'admin_widget.js' => 'module_theme'
		));
		$this->view->aLayouts = $aLayouts;
		$aBreadCrumb = array(
			'title' => $this->language()->translate('theme.layouts'),
			'extra_title' => '',
			'icon' => '',
			'url' => 'javascript:void(0);',
			'title_extra' => '',
			'path' => array(
					$this->url()->makeUrl('theme/layouts', array('admincp' => true)) => $this->language()->translate('theme.layouts'),
			),
		);
		$this->template()->setBreadCrumb($aBreadCrumb);
	}
	public function EditAction()
	{

		$oLayoutModel = (new Layout());
		$iId = $this->request()->get('id');
		$oLayout = $oLayoutModel->getOne($iId);
		if($this->request()->isAjax())
		{
			$bHasPerm = $this->auth()->acl()->hasPerm('page.can_design_layout');
			if (!$bHasPerm)
			{
				$aReturn = array(
					'status' => 0,
					'message' => $this->language()->translate('core.you_does_not_have_permission_to_access_this_area')
				);
			}
			else
			{
				$aReturn = array(
					'status' => HTTP_CODE_BAD_REQUEST,
					'message' => 'Bad Request',
				);
			}

			if(!$oLayout || !$oLayout->layout_id)
			{
				$aReturn['message'] = $this->language()->translate('core.no_items_found');
			}
			else
			{
				$oLayout->header = (int) (!$this->request()->get('header',false));
                $oLayout->footer = (int) (!$this->request()->get('footer',false));
                $oLayout->update();
				$aLocations = $this->request()->get('locations');
				$oLayoutDesign = new LayoutDesign();
				$oWidgetModel = new WidgetModel();
				if (count($aLocations))
				{
					$bSuccess = true;
					foreach ($aLocations as $Key => $aLocation)
					{
						if (isset($aLocation['widgets']) && count($aLocation['widgets']) > 0)
						{
							$iCnt = 0;
							foreach ($aLocation['widgets'] as $aWigetInfo)
							{
								$iCnt++;
								$iPwId = isset($aWigetInfo['pw_id']) ? $aWigetInfo['pw_id'] : 0;
								$oWidgetInstance = $oLayoutDesign->getOne($iPwId);
								$sHash = isset($aWigetInfo['ehash']) ? $aWigetInfo['ehash']: "";
								$aSubmitData = $oWidgetModel->getFromSession($sHash);
								$aParamValues = isset($aSubmitData['params']) ? $aSubmitData['params'] : array();
								if($oWidgetInstance && $oWidgetInstance->pw_id)
								{
									if(isset($aWigetInfo['remove']) && $aWigetInfo['remove'] == 1)
									{
										$oWidgetInstance->delete();
									}
									else
									{
										if($aSubmitData)
										{
											$oWidgetInstance->param_values = $aParamValues;
										}
										$oWidgetInstance->location_id = $aLocation['id'];
										$oWidgetInstance->ordering = $iCnt;
										$oWidgetInstance->update();
									}
								}
								else
								{
									if(!empty($sHash))
									{
										$aInsert = array(
											'widget_id' => $aWigetInfo['widget_id'],
											'location_id' => $aLocation['id'],
											'param_values' => $aParamValues,
											'ordering' => $iCnt,
											'layout_id' => $iId
										);
										$oRow = $oLayoutDesign->getTable()->createRow($aInsert);
										try
										{
											$oRow->save();
										}
										catch(\Exception $ex)
										{
											$aReturn['message'] = $ex->getMessage();
											$bSuccess = false;
										}
									}
								}
							}
						}
					}
				}
				$oWidgetModel->cleanSession();
				if($bSuccess)
				{
					$aReturn['status'] = 1;
					$aReturn['message'] = $this->language()->translate('theme.updated_layout_design_successfully');
				}
			}
			system_display_result($aReturn);
		}
		if(!$oLayout || !$oLayout->layout_id)
		{
			$this->url()->redirect('theme/layout',array('admincp' => true),$this->language()->translate('theme.no_layout_found'),'error');
		}
		$sLayoutName = $oLayout->layout_name;
		$aParams = $oLayout->params;
		$this->view->oLayout = $oLayout;
		$this->app()->setSharedData('design-mode', true);
		$this->app()->setSharedData('design-layout', true);
		$this->app()->setSharedData('layout-id', $oLayout->layout_id);
		$this->view->sLayoutPagePathFile = $sLayoutPagePathFile = $oLayout->getLayoutFileName();
		$this->view->aRegisteredWidgets = $aRegisteredWidgets = (new WidgetModel())->getAllByArray();
		$this->view->bIsHideHeader = $bIsHideHeader = (int)(!$oLayout->header);
		$this->view->bIsHideFooter = $bIsHideFooter = (int)(!$oLayout->footer);
		$this->view->bIsDesignLayout = true;

		$this->template()->setHeader(array(
			'<script>var DESGIN_LAYOUT_MODE = true;</script>',
			'jquery-ui.min.js' => 'module_core',
			'jquery-ui.min.css' => 'module_core',
			'jquery.filemanager.js' => 'module_core',
			'jquery.filemanager.css' => 'module_core',
			'tinymce/tinymce.min.js' => 'module_core',
			'theme_design.css' => 'module_theme',
			'theme_layout.css' => 'module_theme',
			'admin_widget.js' => 'module_theme'
		));
		$aBreadCrumb = array(
			'title' => $this->language()->translate('theme.design_layout') . " #".$oLayout->layout_id .' - '. $oLayout->layout_title,
			'extra_title' => '',
			'icon' => '',
			'url' => 'javascript:void(0);',
			'title_extra' => '',
			'path' => array(
					$this->url()->makeUrl('theme/layouts', array('admincp' => true)) => $this->language()->translate('theme.layouts'),
			),
		);
		$this->template()->setBreadCrumb($aBreadCrumb);
	}
    public function DesignAction()
    {
        $iId = $this->request()->get('id');
        $iItemId = $this->request()->get('item-id');
        $sType = $this->request()->get('item-type');
        $aLayout = (new Layout())->getOne($iId);
        $this->view->bIsHideHeader = $bIsHideHeader = $this->request()->get('hide-header');
        $this->view->bIsHideFooter = $bIsHideFooter = $this->request()->get('hide-footer');
        $this->view->bIsDesignLayout = true;
        if ($aLayout)
        {
            $sLayoutPagePathFile = $aLayout->getLayoutFileName();
        }
        else
        {
            $sLayoutPagePathFile =APP_THEME_PATH . 'PageLayout' . APP_DS . "Default.tpl";
        }
        $oControllerModel = new ModuleController();
        $oPageModel = new PageModel();
        $oExistedPage = null;
        $iLayoutDefaultItem = null;
        switch($sType)
        {
        	case 'controller':
        		$oExistedPage = $oControllerModel->getOne ( $iItemId );
        		$iLayoutDefaultItem = $oExistedPage->layout_id;
        		break;
        	case 'page':
        	default:
        		$oExistedPage = $oPageModel->getOne ( $iItemId );
        		$iLayoutDefaultItem = $oExistedPage->page_layout;
        		break;
        }
        if($oExistedPage)
        {
        	$this->app->setSharedData('default-item-layout-id', $iLayoutDefaultItem);
        }
        $this->view->sLayoutPagePathFile = $sLayoutPagePathFile;
        $this->app()->setSharedData('design-mode', true);
        $this->app()->setSharedData('item-id', $iItemId);
        $this->app()->setSharedData('item-type', $sType);
        $this->app()->setSharedData('layout-id', $iId);
        $this->view->aRegisteredWidgets = $aRegisteredWidgets = (new WidgetModel())->getAllByArray();
        $this->view->aItemWidgets = $aItemWidgets = (new LayoutWidgets())->getByItem($iItemId, $sType);
        //$this->view->iItemId = $iItemId;
        //$this->view->sItemType = $sType;
        $this->template()->setHeader(array(
            'jquery-ui.min.js' => 'module_core',
            'jquery-ui.min.css' => 'module_core',
            'theme_design.css' => 'module_theme',
            'theme_layout.css' => 'module_theme',
            'admin_widget.js' => 'module_theme'
        ));
    }
    public function DeleteAction()
    {
    	$this->auth()->acl()->hasPerm('core.can_delete_layout', true);
    	$oLayoutModel = new Layout();
    	$iId = $this->request()->get('id');

    	$this->view->oExistedLayut = $oExistedLayout = $oLayoutModel->getOne($iId);

    	if (!$oExistedLayout || !$oExistedLayout->layout_id)
    	{
    		$this->app->flash->set($this->language()->translate('theme.layout_not_found'));
    		$this->url()->redirect('theme/layout', array('admincp' => true));
    	}
    	if ($oExistedLayout->delete())
    	{
    		$this->app->flash->set($this->language()->translate('theme.deleted_layout_successfully'));
    		$this->url()->redirect('theme/layout', array('admincp' => true));
    	} else
    	{
    		throw new AppException($this->language()->translate('theme.cannot_delete_this_layout'), HTTP_CODE_BAD_REQUEST);
    	}
    }
    public function UpdateAllAction()
    {
    	$oLayoutModel = new Layout();
    	$aVals = $this->request()->getParams(true);
    	if (isset($aVals['is_default']) && $aVals['is_default'] > 0)
    	{
    		$oLayoutModel->setDefault($aVals['is_default']);
    	}
    	die();
    }
}
