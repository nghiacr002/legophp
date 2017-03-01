<?php

namespace APP\Application\Module\Core;

use APP\Engine\Module\Controller;
use APP\Application\Module\Core\Model\Module as ModelModule;

class AdminModuleController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->auth()->acl()->hasPerm('core.can_access_module_page', true);
    }

    public function IndexAction()
    {
        $oModelModule = (new ModelModule());
        $aInstalledModules = $this->app->module->getInstalledModules();
        $this->template()->setHeader(array(
            'bootstrap-switch.min.js' => 'module_core',
            'bootstrap-switch.min.css' => 'module_core',
            'admin_module.js' => 'module_core'
        ));
        $this->view->aInstalledModules = $aInstalledModules;
        $aBreadCrumb = array(
            'title' => $this->language()->translate('core.modules'),
            'extra_title' => '',
            'icon' => '',
            'url' => 'javascript:void(0);',
            'title_extra' => '',
            'path' => array(
                $this->url()->makeUrl('core/module', array('admincp' => true)) => $this->language()->translate('core.module'),
            ),
        );
        $this->template()->setBreadCrumb($aBreadCrumb);
    }

}
