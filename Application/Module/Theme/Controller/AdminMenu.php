<?php

namespace APP\Application\Module\Theme;

use APP\Engine\Module\Controller;
use APP\Application\Module\Theme\Model\Menu as MenuModel;

class AdminMenuController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->auth()->acl()->hasPerm('core.can_access_menu_page', true);
    }

    public function IndexAction()
    {
        $aMenus = (new MenuModel())->getMenusByType('main_menu', 0, true);
        $this->view->aMenus = $aMenus;
        $this->template()->setHeader(array(
            'jquery-ui.min.js' => 'module_core',
            'jquery-ui.min.css' => 'module_core',
            'jquery.mjs.nestedSortable.js' => 'module_core',
            'bootstrap-switch.min.js' => 'module_core',
            'bootstrap-switch.min.css' => 'module_core',
            'menu.js' => 'module_theme',
            'menu.css' => 'module_theme'
        ));
        $sThemUrl = $this->url()->makeUrl('theme/browse', array('admincp' => true));
        $aBreadCrumb = array(
            'title' => $this->language()->translate('core.menu'),
            'icon' => '',
            'url' => 'javascript:void(0);',
            'title_extra' => '',
            'path' => array(
                $sThemUrl => $this->language()->translate('core.theme'),
            ),
        );
        $this->template()->setBreadCrumb($aBreadCrumb);
		$this->view->aCustomMenuItems = $aCustomMenuItems = $this->helper->callback('getCustomMenuItems',array('group_item' => true));
    }

    public function EditAction()
    {
        if ($this->request()->isPost())
        {
            $iId = $this->request()->get('menu_id');
            $oMenuItem = (new MenuModel())->getOne($iId);
            $aData = $this->request()->getParams();
            if (!isset($aData['is_active']))
            {
                $aData['is_active'] = 0;
            }
            unset($aData['router']);
            $oMenuItem->setData($aData);
            if ($oMenuItem->update())
            {
                $oMenuItem->is_updated = 1;
            }
        } else
        {
            $iId = $this->request()->get('id');
            $oMenuItem = (new MenuModel())->getOne($iId);
        }
        if ($oMenuItem && $oMenuItem->menu_id)
        {
            system_display_result(array(
                'menu' => $oMenuItem->getProps(),
            ));
        }
    }

    public function AddAction()
    {
        $oMenuModel = new MenuModel();
        $aDataRow = $this->request()->getParams();
        if (isset($aDataRow['router']))
        {
            unset($aDataRow['router']);
        }
        if (isset($aDataRow['menu_id']) && empty($aDataRow['menu_id']))
        {
        	unset($aDataRow['menu_id']);
        }
        $oNewMenu = $oMenuModel->getTable()->createRow($aDataRow);
        if ($oNewMenu->isValid())
        {
            $oNewMenu->ordering = APP_TIME;
            if ($iMenuId = $oNewMenu->save())
            {
                $oNewMenu->menu_id = $iMenuId;
                system_display_result(array(
                    'menu' => $oNewMenu->getProps(),
                ));
            } else
            {
                system_display_result(array(
                    "message" => $this->language()->get('core.cannot_add_new_menu'),
                    "code" => HTTP_CODE_BAD_REQUEST
                ));
            }
        } else
        {
            system_display_result(array(
                "message" => $oNewMenu->getErrors(),
                "code" => HTTP_CODE_BAD_REQUEST
            ));
        }
    }

    public function UpdateItemsAction()
    {
        $aItems = $this->request()->getParams();
        $aItems = isset($aItems['menu-item']) ? $aItems['menu-item'] : array();
        $oMenuModel = new MenuModel();
        if (count($aItems))
        {
            $iCnt = 0;
            foreach ($aItems as $iMenuId => $iParentId)
            {
                $iCnt++;
                $oMenuItem = $oMenuModel->getOne($iMenuId);
                if ($oMenuItem && $oMenuItem->menu_id)
                {
                    $oMenuItem->parent_id = (int) $iParentId;
                    $oMenuItem->ordering = $iCnt;
                    $oMenuItem->update();
                }
            }
            system_display_result(array(
                "message" => $this->language()->translate('core.updated_menu_successfully'),
            ));
        }
    }

    public function DeleteAction()
    {
        $iId = $this->request()->get('id');
        $oMenuItem = (new MenuModel())->getOne($iId);
        if ($oMenuItem && $oMenuItem->menu_id)
        {
            if ($oMenuItem->delete())
            {
                system_display_result(array(
                    "message" => $this->language()->translate('core.deleted_menu_successfully')
                ));
            }
        }
    }

}
