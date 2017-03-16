<?php

namespace APP\Application\Module\Core;

use APP\Engine\Module\Controller;
use APP\Application\Module\Core\Model\Category as ModelCategory;

class AdminCategoryController extends Controller
{

    protected $_sType;

    protected function afterInitialize()
    {
        $this->_sType = $this->request()->get('type');
        $this->view->sCurrentType = $this->_sType;
    }

    public function IndexAction()
    {
        $oModelCategory = (new ModelCategory($this->_sType));

        $aCategories = $oModelCategory->getCategoriesByType($this->_sType, 0, true);
        $this->template()->setHeader(array(
            'jquery-ui.min.js' => 'module_core',
            'jquery-ui.min.css' => 'module_core',
            'jquery.mjs.nestedSortable.js' => 'module_core',
            'bootstrap-switch.min.js' => 'module_core',
            'bootstrap-switch.min.css' => 'module_core',
            'admin_category.css' => 'module_core',
            'admin_category.js' => 'module_core'
        ));
        $this->view->aCategories = $aCategories;
        $aBreadCrumb = array(
            'title' => $this->language()->translate('core.manage_categories'),
            'extra_title' => '',
            'icon' => '',
            'url' => 'javascript:void(0);',
            'title_extra' => '',
            'path' => array(
                $this->url()->makeUrl('core/category', array('type' => $this->_sType, 'admincp' => true)) => $this->language()->translate('core.manage_category'),
            ),
        );
        $this->template()->setBreadCrumb($aBreadCrumb);
    }

    public function EditAction()
    {
        if ($this->request()->isPost())
        {
            $iId = $this->request()->get('category_id');
            $oCategoryItem = (new ModelCategory($this->_sType))->getOne($iId);
            $aData = $this->request()->getParams();
            if (!isset($aData['is_active']))
            {
                $aData['is_active'] = 0;
            }
            unset($aData['router']);
            $oCategoryItem->setData($aData);
            if ($oCategoryItem->update())
            {
                $oCategoryItem->is_updated = 1;
            }
        } else
        {
            $iId = $this->request()->get('id');
            $oCategoryItem = (new ModelCategory($this->_sType))->getOne($iId);
        }
        if ($oCategoryItem && $oCategoryItem->category_id)
        {
            system_display_result(array(
                'category' => $oCategoryItem->getProps(),
            ));
        }
    }

    public function AddAction()
    {
        $oModelCategory = new ModelCategory($this->_sType);
        $aDataRow = $this->request()->getParams();
        if (isset($aDataRow['router']))
        {
            unset($aDataRow['router']);
        }
        if (isset($aDataRow['category_id']))
        {
        	unset($aDataRow['category_id']);
        }
        $oNewCategory = $oModelCategory->getTable()->createRow($aDataRow);

        if ($oNewCategory->isValid())
        {
            $oNewCategory->ordering = APP_TIME;
            if ($iId = $oNewCategory->save())
            {
                $oNewCategory->category_id = $iId;
                system_display_result(array(
                    'category' => $oNewCategory->getProps(),
                ));
            } else
            {
                system_display_result(array(
                    "message" => $this->language()->get('core.cannot_add_new_category'),
                    "code" => HTTP_CODE_BAD_REQUEST
                ));
            }
        } else
        {
            system_display_result(array(
                "message" => $oNewCategory->getErrors(),
                "code" => HTTP_CODE_BAD_REQUEST
            ));
        }
    }

    public function UpdateItemsAction()
    {
        $aItems = $this->request()->getParams();
        $aItems = isset($aItems['category-item']) ? $aItems['category-item'] : array();
        $oCategoryModel = new ModelCategory($this->_sType);
        if (count($aItems))
        {
            $iCnt = 0;
            foreach ($aItems as $iCategoryId => $iParentId)
            {
                $iCnt++;
                $oItem = $oCategoryModel->getOne($iCategoryId);
                if ($oItem && $oItem->category_id)
                {
                    $oItem->parent_id = (int) $iParentId;
                    $oItem->ordering = $iCnt;
                    $oItem->update();
                }
            }
            system_display_result(array(
                "message" => $this->language()->translate('core.updated_category_successfully'),
            ));
        }
    }

    public function DeleteAction()
    {
        $iId = $this->request()->get('id');
        $oCategoryModel = new ModelCategory($this->_sType);
        $oItem = $oCategoryModel->getOne($iId);
        if ($oItem && $oItem->category_id)
        {
            if ($oItem->delete())
            {
                system_display_result(array(
                    "message" => $this->language()->translate('core.deleted_category_successfully')
                ));
            }
        }
    }

}
