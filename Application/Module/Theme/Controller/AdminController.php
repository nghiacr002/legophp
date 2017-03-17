<?php
namespace APP\Application\Module\Theme;
use APP\Engine\Module\Controller;
use APP\Application\Module\Theme\Model\ModuleController;
use APP\Application\Module\Theme\Model\Layout;
use APP\Application\Module\Theme\Form\ModuleControllerItem;
use APP\Application\Module\Theme\Model\LayoutDesign;
use APP\Application\Module\Theme\Model\LayoutWidgets;
class AdminControllerController extends Controller
{
	public function __construct()
	{
		parent::__construct();
		$aMenus = array(
			'add-new' => array(
				'title' => $this->language()->translate('core.add_new'),
				'action' => $this->url()->makeUrl('theme/controller/add',array('admincp' => true)),
				'custom' => '',
				'class' => 'btn btn-warning add-new-controller'
			)
		);
		$this->app()->setSharedData('custom-menu-header',$aMenus);
		$this->template()->setHeader(array(
			'admin_controller.js' => 'module_core'
		));
	}
	public function IndexAction()
	{
		$oModuleControllerModel = (new ModuleController());
		$this->view->aLayouts = $aLayouts = $oModuleControllerModel->getAll();
		$this->template()->setHeader(array(
				'bootstrap-switch.min.js' => 'module_core',
				'bootstrap-switch.min.css' => 'module_core',
				'admin_widget.js' => 'module_theme'
		));
		$aBreadCrumb = array(
				'title' => $this->language()->translate('theme.module_controller'),
				'extra_title' => '',
				'icon' => '',
				'url' => 'javascript:void(0);',
				'title_extra' => '',
				'path' => array(
						$this->url()->makeUrl('theme/controller', array('admincp' => true)) => $this->language()->translate('theme.module_controller'),
				),
		);
		$this->template()->setBreadCrumb($aBreadCrumb);
	}
	public function AddAction()
	{
		$this->view->iPopupId = $this->request()->get('popup_id');
		$this->view->aModules = $this->app->module->getInstalledModules();
		$this->view->aLayouts = (new Layout())->getAll();
		if ($this->request()->isPost() && $this->request()->get('action') == "submit")
		{
			$aReturn = array(
        		'code' => HTTP_CODE_OK,
        		'message' => '',
        		'params' => array(),
        	);
        	$aDataSubmit = $this->request()->getParams();
        	$sRouter = isset($aDataSubmit['router_name']) ? $aDataSubmit['router_name'] : "";
        	try
        	{
        		unset($aDataSubmit['action']);
        		$oRow = (new ModuleController())->getTable()->createRow($aDataSubmit);
        		if($oRow->isValid())
        		{
        			$iId = $oRow->save();
        			if($iId)
        			{
        				//create new default widget

        				$aDefaultLayoutWidgets = (new LayoutDesign())->getDefaultWidgets($oRow->layout_id);
						if(count($aDefaultLayoutWidgets))
						{
							$oLayoutWidgetModel = new LayoutWidgets();
							foreach($aDefaultLayoutWidgets as $iKey => $oWidget)
							{
								try
								{
									$oNewLayoutWidget = $oLayoutWidgetModel->getTable()->createRow();
									$oNewLayoutWidget->item_id = $iId;
									$oNewLayoutWidget->item_type = 'controller';
									$oNewLayoutWidget->widget_id = $oWidget->widget_id;
									$oNewLayoutWidget->layout_id = $oWidget->layout_id;
									$oNewLayoutWidget->location_id = $oWidget->location_id;
									$oNewLayoutWidget->param_values = $oWidget->param_values;
									$oNewLayoutWidget->ordering = $oWidget->ordering;
									$oNewLayoutWidget->save();
								}
								catch (\Exception $ex)
								{

								}
							}
						}
        				$aReturn['redirect'] = $this->url()->makeUrl('theme/controller/edit',array('admincp' => true,'id' => $iId));
        			}
        		}
        		else
        		{
        			$aReturn['params'] = $oRow->getErrors();
        			$aReturn['code'] = HTTP_CODE_BAD_REQUEST;
        		}
        	}
        	catch(\Exception $ex)
        	{
        		$aReturn['code'] = $ex->getCode();
        		$aReturn['message'] = $ex->getMessage();
        	}
        	system_display_result($aReturn);
		}
	}
	public function DeleteAction()
	{
		$this->auth()->acl()->hasPerm('core.can_delete_controller_design', true);
		$oModuleController = new ModuleController();
		$iId = $this->request()->get('id');

		$this->view->oExistedLayout = $oExistedLayout = $oModuleController->getOne($iId);

		if (!$oExistedLayout || !$oExistedLayout->controller_id)
		{
			$this->app->flash->set($this->language()->translate('theme.layout_not_found'));
			$this->url()->redirect('theme/controller', array('admincp' => true));
		}
		if ($oExistedLayout->delete())
		{
			$this->app->flash->set($this->language()->translate('theme.deleted_layout_successfully'));
			$this->url()->redirect('theme/controller', array('admincp' => true));
		} else
		{
			throw new AppException($this->language()->translate('theme.cannot_delete_this_layout'), HTTP_CODE_BAD_REQUEST);
		}
	}
	public function EditAction()
	{
		$this->auth()->acl()->hasPerm('theme.can_edit_controller_layout', true);
		$oModuleControllerModel = new ModuleController();
		$iId = $this->request()->get('id');
		$this->view->oExistedLayout = $oExistedLayout = $oModuleControllerModel->getOne($iId);
		if (!$oExistedLayout || !$oExistedLayout->controller_id)
		{
			$this->app->flash->set($this->language()->translate('theme.layout_does_not_exist'));
			$this->url()->redirect('theme/controller', array('admincp' => true));
		}
		$oFormItem = new ModuleControllerItem();
		$this->view->oFormItem = $oFormItem;

		if ($this->request()->isPost())
		{
			$aData = $oFormItem->getFormValues();
			$oExistedLayout->mergeData($aData);
			try
			{
				if ($oExistedLayout->isValid())
				{
					if ($oExistedLayout->update())
					{
						$this->url()->redirect('theme/controller/edit', array('id' => $iId, 'admincp' => true),$this->language()->translate('theme.updated_layout_successfully'));
					}
				}
			}
			catch (AppException $ex)
			{
				Logger::error($ex);
				$this->flash()->set($this->language()->translate('core.there_are_some_problems_with_system_please_try_again'), "system", 'error');
			}

		}
		else
		{
			$aValues = $oExistedLayout->getProps();
			$oFormItem->setFormValues($aValues);
		}
		$this->template()->setHeader(array(
			'jquery-ui.min.js' => 'module_core',
			'jquery-ui.min.css' => 'module_core',
			'jquery.filemanager.js' => 'module_core',
			'jquery.filemanager.css' => 'module_core',
			'tinymce/tinymce.min.js' => 'module_core',
			'ace/ace.js' => 'module_core',
			'ace/mode-javascript.js' => 'module_core',
			'ace/mode-css.js' => 'module_core',
			'module_controller.js' => 'module_theme',
			'admin_page.js' => 'module_page',
			'admin_page.css' => 'module_page',
		));
		$sUrl = $this->url()->makeUrl('theme/controller', array('admincp' => true));

		$aBreadCrumb = array(
				'title' => $oExistedLayout->controller_name,
				'icon' => '',
				'url' => 'javascript:void(0);',
				'title_extra' => '',
				'path' => array(
					$sUrl => $this->language()->translate('theme.module_controller'),
				),
		);

		$this->view->sFormUrl = $this->url()->makeUrl('theme/controller/edit', array('id' => $iId, 'admincp' => true));
		$this->template()->setBreadCrumb($aBreadCrumb);
		$this->setActiveMenu('theme/controller');
	}
}
