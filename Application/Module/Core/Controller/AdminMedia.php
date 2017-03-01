<?php

namespace APP\Application\Module\Core;

use APP\Engine\Module\Controller;
use APP\Application\Module\Core\Model\Media;

class AdminMediaController extends Controller
{

    public function IndexAction()
    {
        $this->template()->assign(array(
            'sPathDefault' => APP_UPLOAD_PATH
        ));
        $this->template()->setHeader(array(
            'jquery.filemanager.js' => 'module_core',
            'jquery.filemanager.css' => 'module_core',
            'media.js' => 'module_core',
            'media.css' => 'module_core',
        ));
        $sAdminPath = $this->app->getConfig('system', 'admin_path');
        $aBreadCrumb = array(
            'title' => $this->language()->translate('core.media'),
            'icon' => '',
            'url' => 'javascript:void(0);',
            'title_extra' => '',
            'path' => array(
                $sAdminPath => $this->language()->translate('core.media'),
            ),
        );
        $this->template()->setBreadCrumb($aBreadCrumb);
    }

}
