<?php

namespace APP\Application\Module\Page;

use APP\Engine\Module\Controller;
use APP\Application\Module\Page\Model\Page as PageModel;
use APP\Engine\HTML\Filter;
use APP\Application\Module\Page\Form\PageItem;
use APP\Engine\HTML\Pagination;
use APP\Application\Module\Core\Model\HashTag;
use APP\Application\Module\Core\Model\MetaTag;
use APP\Application\Module\Theme\Model\DbTable\DbRow\LayoutWidgets;
use APP\Engine\AppException;
use APP\Application\Module\Theme\Model\Widget as WidgetModel;
use APP\Application\Module\Theme\Model\ModuleController;

class AdminIndexController extends Controller
{
	public function IndexAction()
	{
		$this->url ()->redirect ( 'page/manage', array (
				'admincp' => true
		) );
	}
	public function DeleteAction()
	{
		$this->auth ()->acl ()->hasPerm ( 'core.can_delete_page', true );
		$oPageModel = new PageModel ();
		$iId = $this->request ()->get ( 'id' );
		$this->view->oExistedPage = $oExistedPage = $oPageModel->getOne ( $iId );
		if (! $oExistedPage || ! $oExistedPage->page_id)
		{
			$this->app->flash->set ( $this->laguage ()->translate ( 'page.page_does_not_exist' ) );
			$this->url ()->redirect ( 'page/manage', array (
					'admincp' => true
			) );
		}
		if ($oExistedPage->delete ())
		{
			$this->app->flash->set ( $this->language ()->translate ( 'page.deleted_page_successfully' ) );
			$this->url ()->redirect ( 'page/manage', array (
					'admincp' => true
			) );
		} else
		{
			throw new AppException ( $this->language ()->translate ( 'page.cannot_delete_this_blog' ), HTTP_CODE_BAD_REQUEST );
		}
	}
	public function DesignAction()
	{
		$bHasPerm = $this->auth ()->acl ()->hasPerm ( 'page.can_edit_page' );
		if (! $bHasPerm)
		{
			$aReturn = array (
					'status' => 0,
					'message' => $this->language ()->translate ( 'core.you_does_not_have_permission_to_access_this_area' )
			);
		}
		else
		{
			$aReturn = array (
					'status' => HTTP_CODE_BAD_REQUEST,
					'message' => ''
			);
			$iPageId = $this->request ()->get ( 'pid' );
			$iLayoutId = $this->request ()->get ( 'id' );
			$aLocations = $this->request ()->get ( 'locations' );
			$sType = $this->request()->get('item_type','page');
			$oPageModel = new PageModel ();
			$oControllerModel = new ModuleController();
			$oWidgetModel = new WidgetModel ();
			$oExistedPage = null;
			switch($sType)
			{
				case 'controller':
					$oExistedPage = $oControllerModel->getOne ( $iPageId );
					break;
				case 'page':
				default:
					$oExistedPage = $oPageModel->getOne ( $iPageId );
					break;
			}

			if (!$oExistedPage)
			{
				$aReturn ['message'] = $this->language ()->translate ( 'core.no_items_found' );
			}
			else
			{
				if($sType == "controller")
				{
					$oExistedPage->layout_id = ( int ) $iLayoutId;
				}
				else
				{
					$oExistedPage->page_layout = ( int ) $iLayoutId;
				}
				$oExistedPage->hide_header_layout = ( int ) $this->request ()->get ( 'header' );
				$oExistedPage->hide_footer_layout = ( int ) $this->request ()->get ( 'footer' );
				if ($oExistedPage->update ())
				{
					$oPageWidgetModel = new \APP\Application\Module\Theme\Model\LayoutWidgets ();
					$oPageWidgetModel->setItem ( $iPageId,$sType);
					$bSuccess = true;
					if (count ( $aLocations ))
					{
						foreach ( $aLocations as $Key => $aLocation )
						{
							if (isset ( $aLocation ['widgets'] ) && count ( $aLocation ['widgets'] ) > 0)
							{
								$iCnt = 0;
								foreach ( $aLocation ['widgets'] as $aWigetInfo )
								{
									$iCnt ++;
									$iPwId = isset ( $aWigetInfo ['pw_id'] ) ? $aWigetInfo ['pw_id'] : 0;
									$oWidgetInstance = $oPageWidgetModel->getOne ( $iPwId );
									$sHash = isset ( $aWigetInfo ['ehash'] ) ? $aWigetInfo ['ehash'] : "";
									$aSubmitData = $oWidgetModel->getFromSession ( $sHash );
									$aParamValues = isset ( $aSubmitData ['params'] ) ? $aSubmitData ['params'] : array ();
									$bIsInsert = true;
									if($oWidgetInstance && $oWidgetInstance->pw_id)
									{
										if($oWidgetInstance->layout_id == $iLayoutId
												&& $oWidgetInstance->item_id == $iPageId
												&& $oWidgetInstance->item_type == $sType
											){
											$bIsInsert = false;
										}
									}
									if (!$bIsInsert)
									{
										if (isset ( $aWigetInfo ['remove'] ) && $aWigetInfo ['remove'] == 1)
										{
											$oWidgetInstance->delete ();
										}
										else
										{
											if ($aSubmitData)
											{
												$oWidgetInstance->param_values = $aParamValues;
											}
											$oWidgetInstance->ordering = $iCnt;
											$oWidgetInstance->update ();
										}
									}
									else
									{
										if (! empty ( $sHash ))
										{
											$aInsert = array (
													'item_id' => $iPageId,
													'widget_id' => $aWigetInfo ['widget_id'],
													'item_type' => $sType,
													'location_id' => $aLocation ['id'],
													'param_values' => $aParamValues,
													'ordering' => $iCnt,
													'layout_id' => $iLayoutId
											);
											$oRow = $oPageWidgetModel->getTable ()->createRow ( $aInsert );
											try
											{
												$oRow->save ();
											} catch ( \Exception $ex )
											{
												$aReturn ['message'] = $ex->getMessage ();
												$bSuccess = false;
											}
										}
									}
								}
							}
						}
					}
					$oWidgetModel->cleanSession ();
					if ($bSuccess)
					{
						$aReturn ['status'] = 1;
						$aReturn ['message'] = $this->language ()->translate ( 'page.updated_page_design_successfully' );
					}
				}
			}
		}
		system_display_result ( $aReturn );
	}
	public function EditAction()
	{
		$this->auth ()->acl ()->hasPerm ( 'page.can_edit_page', true );
		$oPageModel = new PageModel ();
		$iId = $this->request ()->get ( 'id' );
		$this->view->oExistedPage = $oExistedPage = $oPageModel->getOne ( $iId );
		if (! $oExistedPage || ! $oExistedPage->page_id)
		{
			$this->app->flash->set ( $this->language ()->translate ( 'page.page_does_not_exist' ) );
			$this->url ()->redirect ( 'page/manage', array (
					'admincp' => true
			) );
		}
		$oFormPageItem = new PageItem ();
		$this->view->oFormPageItem = $oFormPageItem;
		if ($this->request ()->isPost ())
		{
			if ($oFormPageItem->isValid ())
			{
				try
				{
					$aData = $oFormPageItem->getFormValues ();
					$sHashTag = "";
					if (isset ( $aData ['hashtag'] ))
					{
						$sHashTag = $aData ['hashtag'];
						unset ( $aData ['hashtag'] );
					}
					$aData ['page_id'] = $oExistedPage->page_id;
					$oExistedPage->setData ( $aData );
					if ($oExistedPage->isValid ())
					{
						if ($oExistedPage->update ())
						{
							$this->app->flash->set ( $this->language ()->translate ( 'page.updated_page_successfully' ) );
						}
						// update hashtag
						(new HashTag ())->updateHash ( $sHashTag, $oExistedPage->page_id, "page" );
						// working with meta tags
						$aMetaTags = $this->request ()->get ( 'meta' );
						(new MetaTag ())->updateMetags ( $aMetaTags, "page", $oExistedPage->page_id );
						$this->url ()->redirect ( 'page/edit', array (
								'id' => $iId,
								'admincp' => true
						) );
					} else
					{
						$aErrors = $oExistedPage->getErrors ();
						$this->flash ()->set ( $aErrors, "system", 'error' );
					}
				} catch ( AppException $ex )
				{
					Logger::error ( $ex );
					$this->flash ()->set ( $this->language ()->translate ( 'core.there_are_some_problems_with_system_please_try_again' ), "system", 'error' );
				}
			} else
			{
				$aMessages = $oFormPageItem->getMessages ();
				foreach ( $aMessages as $sId => $sMessage )
				{
					$this->flash ()->set ( $sMessage, $sId, 'error' );
				}
			}
		} else
		{
			$aValues = $oExistedPage->getProps ();
			$aValues ['hashtag'] = (new HashTag ())->getByItem ( $oExistedPage->page_id, "page" );
			$oFormPageItem->setFormValues ( $aValues );
		}
		$this->template ()->setHeader ( array (
				'jquery-ui.min.js' => 'module_core',
				'jquery-ui.min.css' => 'module_core',
				'jquery.filemanager.js' => 'module_core',
				'jquery.filemanager.css' => 'module_core',
				'tinymce/tinymce.min.js' => 'module_core',
				'ace/ace.js' => 'module_core',
				'ace/mode-javascript.js' => 'module_core',
				'ace/mode-css.js' => 'module_core',
				'admin_page.js' => 'module_page',
				'admin_page.css' => 'module_page'
		) );

		$sUrl = $this->url ()->makeUrl ( 'page/manage', array (
				'admincp' => true
		) );
		$aBreadCrumb = array (
				'title' => $this->language ()->translate ( 'page.page' ),
				'icon' => '',
				'url' => 'javascript:void(0);',
				'title_extra' => '',
				'path' => array (
						$sUrl => $this->language ()->translate ( 'page.manage' )
				)
		);

		$this->view->sFormUrl = $this->url ()->makeUrl ( 'page/edit', array (
				'id' => $iId,
				'admincp' => true
		) );
		$this->template ()->setBreadCrumb ( $aBreadCrumb );
		$this->view->setLayout ( 'Add.tpl' );
		$this->setActiveMenu ( 'page/manage/index' );
	}
	public function AddAction()
	{
		$this->auth ()->acl ()->hasPerm ( 'page.can_add_page', true );
		$oFormPageItem = new PageItem ();
		$this->view->oFormPageItem = $oFormPageItem;
		if ($this->request ()->isPost ())
		{
			if ($oFormPageItem->isValid ())
			{
				try
				{
					$aData = $oFormPageItem->getFormValues ();
					$sHashTag = "";
					if (isset ( $aData ['hashtag'] ))
					{
						$sHashTag = $aData ['hashtag'];
						unset ( $aData ['hashtag'] );
					}
					$oPageModel = new PageModel ();
					$oNewPage = $oPageModel->getTable ()->createRow ( $aData );
					$oNewPage->created_time = APP_TIME;
					$oNewPage->page_layout = 0;
					$oNewPage->owner_id = $this->auth ()->getViewer ()->user_id;
					if ($oNewPage->isValid ())
					{
						$iId = $oNewPage->save ();
						if ($iId)
						{
							$oPageWidgetModel = new \APP\Application\Module\Theme\Model\LayoutWidgets ();
							$oPageWidgetModel->setItem ( $iId, "page" );
							// create default widget content
							$oWidgetPageContent = (new WidgetModel ())->getOne ( 'page.page_content', 'widget_name' );
							if ($oWidgetPageContent)
							{
								$aInsert = array (
										'item_id' => $iId,
										'widget_id' => $oWidgetPageContent->widget_id,
										'item_type' => 'page',
										'location_id' => 1,
										'layout_id' => 0,
										'param_values' => NULL,
										'ordering' => APP_TIME
								);
								$oRow = $oPageWidgetModel->getTable ()->createRow ( $aInsert );
								try
								{
									$oRow->save ();
								} catch ( \Exception $ex )
								{
								}
							}
							if ($sHashTag)
							{
								(new HashTag ())->insertHash ( $sHashTag, $iId, "page" );
							}
							$aMetaTags = $this->request ()->get ( 'meta' );
							(new MetaTag ())->updateMetags ( $aMetaTags, "page", $iId );
							$this->app->flash->set ( $this->language ()->translate ( 'page.added_new_page_successfully' ) );
							$this->url ()->redirect ( 'page/edit', array (
									'id' => $iId,
									'admincp' => true
							) );
						}
					} else
					{
						$aErrors = $oNewUser->getErrors ();
						$this->flash ()->set ( $aErrors, "system", 'error' );
					}
				} catch ( AppException $ex )
				{
					Logger::error ( $ex );
					$this->flash ()->set ( $this->language ()->translate ( 'core.there_are_some_problems_with_system_please_try_again' ), "system", 'error' );
				}
			} else
			{
				$aMessages = $oFormPageItem->getMessages ();
				foreach ( $aMessages as $sId => $sMessage )
				{
					$this->flash ()->set ( $sMessage, $sId, 'error' );
				}
			}
		}

		$this->template ()->setHeader ( array (
				'jquery-ui.min.js' => 'module_core',
				'jquery-ui.min.css' => 'module_core',
				'jquery.filemanager.js' => 'module_core',
				'jquery.filemanager.css' => 'module_core',
				'tinymce/tinymce.min.js' => 'module_core',
				'ace/ace.js' => 'module_core',
				'ace/mode-javascript.js' => 'module_core',
				'ace/mode-css.js' => 'module_core',
				'admin_page.js' => 'module_page',
				'admin_page.css' => 'module_page'
		) );
		$sUrl = $this->url ()->makeUrl ( 'page/manage', array (
				'admincp' => true
		) );
		$aBreadCrumb = array (
				'title' => $this->language ()->translate ( 'page.page' ),
				'icon' => '',
				'url' => 'javascript:void(0);',
				'title_extra' => '',
				'path' => array (
						$sUrl => $this->language ()->translate ( 'page.manage' )
				)
		);
		$this->view->sFormUrl = $this->url ()->makeUrl ( 'page/add', array (
				'admincp' => true
		) );
		$this->template ()->setBreadCrumb ( $aBreadCrumb );
	}
	public function ManageAction()
	{
		$this->auth ()->acl ()->hasPerm ( 'page.can_access_page', true );
		$oPageModel = (new PageModel ());
		$iLimit = $this->request ()->get ( 'limit', 20 );
		$iCurrentPage = $this->request ()->get ( 'page', 1 );
		$this->view->oFilter = $oFilter = new Filter ();
		$oFilter->setParams ( array (
				'page-title' => array (
						'type' => 'text',
						'name' => 'page_title',
						'placeholder' => $this->language ()->translate ( 'page.enter_keyword_to_search' )
				),
				'search-button' => array (
						'type' => 'search',
						'class' => 'btn btn-success',
						'value' => $this->language ()->translate ( 'core.search' )
				)
		) );
		$aFilter = $oFilter->getFilterValues ();
		$aConds = array ();
		if (isset ( $aFilter ) && count ( $aFilter ))
		{
			foreach ( $aFilter as $iKey => $mValue )
			{
				$aConds [] = array (
						$iKey,
						'%' . $mValue . '%',
						'LIKE'
				);
			}
		}

		$iTotal = $oPageModel->getTotal ( $aConds );
		$aPages = $oPageModel->getAll ( $aConds, $iCurrentPage, $iLimit, '*', array (
				'created_time',
				'DESC'
		) );

		$this->view->iTotal = $iTotal;
		$this->view->aPages = $aPages;
		$sUrl = $this->url ()->makeUrl ( 'page/manage', array (
				'admincp' => true
		) );
		$aBreadCrumb = array (
				'title' => $this->language ()->translate ( 'page.page' ),
				'icon' => '',
				'url' => 'javascript:void(0);',
				'title_extra' => '',
				'path' => array (
						$sUrl => $this->language ()->translate ( 'page.page' )
				)
		);
		$this->template ()->setBreadCrumb ( $aBreadCrumb );
		$aParams = array (
				'router' => 'page/manage',
				'params' => array (
						'admincp' => true,
						'limit' => $iLimit
				),
				'total' => $iTotal,
				'current' => $iCurrentPage,
				'limit' => $iLimit
		);
		if (count ( $aFilter ))
		{
			$aParams ['params'] = array_merge ( $aParams ['params'], $aFilter );
		}
		$this->template()->setHeader(array(
			'bootstrap-switch.min.js' => 'module_core',
			'bootstrap-switch.min.css' => 'module_core',
			'admin_page.js' => 'module_page'
		));
		$this->view->paginator = new Pagination ( $aParams );
	}
	public function UpdateAllAction()
	{
		$oPageModel = new PageModel();
		$aVals = $this->request()->getParams(true);
		if (isset($aVals['is_default']))
		{
			$oPageModel->setLandingPage($aVals['is_default']);
		}
		die();
	}
	public function ResetLandingPageAction()
	{
		$oPageModel = new PageModel();
		$oPageModel->setLandingPage(0);
		$this->url()->redirect('page/manage',array('admincp' => true),$this->language()->translate('page.reset_landing_page_successfully'));
	}
}
