<?php

namespace APP\Application\Module\Theme;

use APP\Engine\Module\Controller;
use APP\Application\Module\Theme\Model\Widget as WidgetModel;
use APP\Application\Module\Theme\Model\LayoutWidgets;
use APP\Application\Module\Theme\Model\LayoutDesign;

class AdminWidgetController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->auth()->acl()->hasPerm('core.can_access_widget_page', true);
    }

    public function IndexAction()
    {
        $oWidgetModel = (new WidgetModel());
        $aWidgets = $oWidgetModel->getAll();
        $this->template()->setHeader(array(
            'admin_widget.js' => 'module_theme'
        ));
        $this->view->aWidgets = $aWidgets;
        $aBreadCrumb = array(
            'title' => $this->language()->translate('theme.widgets'),
            'extra_title' => '',
            'icon' => '',
            'url' => 'javascript:void(0);',
            'title_extra' => '',
            'path' => array(
                $this->url()->makeUrl('theme/widget', array('admincp' => true)) => $this->language()->translate('theme.widgets'),
            ),
        );
        $this->template()->setBreadCrumb($aBreadCrumb);
    }

    public function DeleteAction()
    {
        $iId = $this->request()->get('id');
        $oItem = (new WidgetModel())->getOne($iId);
        if (!$oItem || !$oItem->widget_id)
        {
            $this->app->flash->set($this->language()->translate('theme.widget_does_not_exist'));
            $this->url()->redirect('theme/widget', array('admincp' => true));
        }
        if ($oItem->delete())
        {
            $this->app->flash->set($this->language()->translate('theme.deleted_widget_successfully'));
            $this->url()->redirect('theme/widget', array('admincp' => true));
        } else
        {
            throw new AppException($this->language()->translate('widget.cannot_delete_this_widget'), HTTP_CODE_BAD_REQUEST);
        }
    }

    public function AddAction()
    {
        return false;
        //$this->view->sWidgetType = $sWidgetType = $this->request()->get('widget_type');
        $this->view->iPopupId = $iPopupId = $this->request()->get('popup_id');
        if ($this->request()->isPost() && $this->request()->get('action') == "submit")
        {
            $aReturn = array(
                'code' => HTTP_CODE_BAD_REQUEST,
                'message' => '',
                'params' => array(),
            );
            $aDataSubmit = $this->request()->getParams();
            $oWidgetModel = new WidgetModel();
            $oWidgetItem = $oWidgetModel->getTable()->createRow();
            if (isset($aDataSubmit['params']))
            {
                $aDataSubmit['params'] = serialize($aDataSubmit['params']);
            }
            unset($aDataSubmit['action']);
            $oWidgetItem->setData($aDataSubmit);
            if ($oWidgetItem->isValid())
            {
                //valid
                if ($oWidgetItem->save())
                {
                    $aReturn['code'] = HTTP_CODE_OK;
                    $aReturn['message'] = $this->language()->translate('theme.added_new_widget_html_successfully');
                } else
                {

                }
            } else
            {
                $aReturn['message'] = $this->language()->translate('theme.submit_invalid_data');
                $aReturn['params'] = $oWidgetItem->getErrors();
            }
            system_display_result($aReturn);
        }
    }

    public function EditAction()
    {
    	$sType = $this->request()->get('type');
        $iPWid = $this->request()->get('pwid');
        $iWidgetId = $this->request()->get('wid');
        $oWidgetModel = (new WidgetModel());
        $oWidget = $oWidgetModel->getOne($iWidgetId);
        $sHash = $this->request()->get('ehash');
        if(!$oWidget || !$oWidget->widget_id)
        {
        	system_display_result(array(
        		'status' => 0,
        		'message' => $this->language()->translate('layout.widget_not_found'),
        	));
        }

        $aSettings = $oWidget->params;

        if($sType == "layout")
        {
        	$oWidgetInstance = (new LayoutDesign())->getOne($iPWid);
        }
        else
        {
        	$oWidgetInstance = (new LayoutWidgets())->getOne($iPWid);
        }
        if ($this->request()->isPost() && $this->request()->get('action') == "submit")
        {
        	$aReturn = array(
        			'code' => HTTP_CODE_OK,
        			'message' => '',
        			'params' => array(),
        	);
        	$aDataSubmit = $this->request()->getParams();
        	if(!empty($sHash))
        	{
        		$oWidgetModel->saveToSession($sHash, $aDataSubmit);
        	}
        	system_display_result($aReturn);
        }
        elseif(!empty($sHash))
        {
        	$aDataSubmit = $oWidgetModel->getFromSession($sHash);
        	if(count($aSettings))
        	{
        		if(isset($aDataSubmit['params']))
        		{
        			foreach($aSettings as $sKey => $aSetting)
        			{
        				if(isset($aDataSubmit['params'][$sKey]))
        				{
        					$aSettings[$sKey]['value'] = $aDataSubmit['params'][$sKey];
        				}
        			}
        		}
        		else if($oWidgetInstance && $oWidgetInstance->pw_id){
		        	$aValues = $oWidgetInstance->param_values;

        			foreach($aSettings as $sKey => $aSetting)
        			{
        				if(isset($aValues[$sKey]))
        				{
        					$aSettings[$sKey]['value'] = $aValues[$sKey];
        				}
        			}
		        }

        	}
        }
        $this->view->aSettings = $aSettings;
        $this->view->wid = $iWidgetId;
        $this->view->pwid = $iPWid;
        $this->view->iPopupId = $this->request()->get('popup_id');
        $this->view->sHash = $sHash;
        $sCustomTemplate = $oWidget->params_template;
        if(!empty($sCustomTemplate))
        {
        	$oPlugin = $this->helper->getPlugin('core','AdminWidget');
        	if($oPlugin && method_exists($oPlugin, "getWidgetOptionTemplate"))
        	{
        		$sCustomTemplate = $oPlugin->getWidgetOptionTemplate($oWidget->toArray());
        	}
        	//d($sCustomTemplate);die();
        }
        $this->view->sCustomTemplate = $sCustomTemplate;
    }

}
