<?php

namespace APP\Application\Module\Core;

use APP\Engine\Module\Controller;
use APP\Application\Module\Core\Model\Setting as ModelSetting;
use APP\Engine\AppException;

class AdminSettingController extends Controller
{

    public function IndexAction()
    {
        $sType = $this->request()->get('type');
        if (empty($sType))
        {
            $this->url()->redirect('core/setting', array('admincp' => true, 'type' => 'core'));
        }
        $oSettingModel = new ModelSetting();
        $aConds = array(
            array(
                'module', $sType, "=", ""
            )
        );
        $aSettings = $oSettingModel->getAll($aConds);
        $this->view->aSettings = $aSettings;
        $this->view->sSettingType = $sType;

        $this->template()->setHeader(array(
            'bootstrap-editable.min.js' => 'module_core',
            'bootstrap-editable.css' => 'module_core',
            'setting.js' => 'module_core',
            'setting.css' => 'module_core',
        ));

        $aBreadCrumb = array(
            'title' => $this->language()->translate('core.settings'),
            'extra_title' => '',
            'icon' => '',
            'url' => 'javascript:void(0);',
            'title_extra' => '',
            'path' => array(
                $this->url()->makeUrl('core/setting', array('admincp' => true)) => $this->language()->translate('core.settings'),
            ),
        );
        $this->template()->setBreadCrumb($aBreadCrumb);
    }

    public function UpdateAction()
    {
        if (!$this->auth()->acl()->hasPerm('core.can_update_setting'))
        {
            throw new AppException(
            $this->language()->translate('core.you_does_not_have_permission_to_access_this_area'), HTTP_CODE_FORBIDDEN);
        }
        $iSettingId = $this->request()->get('pk');
        $sValue = $this->request()->get('value');
        $oSetting = (new ModelSetting())->getOne($iSettingId);
        if ($oSetting && $oSetting->setting_id)
        {
            $oSetting->real_value = $sValue;
            if ($oSetting->update())
            {
                system_display_result(array(
                    'code' => HTTP_CODE_OK,
                    'message' => '',
                ));
            }
        }
    }

}
