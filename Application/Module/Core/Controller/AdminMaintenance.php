<?php

namespace APP\Application\Module\Core;

use APP\Engine\Module\Controller;
use APP\Application\Module\Core\Model\Maintenance;
use APP\Engine\Utils;
use APP\Engine\Cache;

class AdminMaintenanceController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->auth()->acl()->hasPerm('core.can_access_menu_page', true);
    }

    public function IndexAction()
    {
        $oMaintenance = (new Maintenance());
        if ($this->request()->isPost())
        {
            $aPaths = $this->request()->get('path');
            if (is_array($aPaths) && count($aPaths))
            {
                foreach ($aPaths as $ikey => $sPath)
                {
                    $oMaintenance->removeItem($sPath);
                }
            }
            $this->url()->redirect('core/maintenance',array('admincp' => true), $this->language()->translate('core.cache_cleaned'));
        }
        $aCacheInfo = Cache::getInstance()->getInfo();
        $this->view->sAdapterCache = $aCacheInfo['adapter'];
        if($this->request()->get('flush'))
        {
        	$oMaintenance->flush();
        	$this->url()->redirect('core/maintenance',array('admincp' => true), $this->language()->translate('core.cache_cleaned'));
        }
        $aFolders = $oMaintenance->getFolders();
        $this->template()->setHeader(array(
            'maintenance.js' => 'module_core'
        ));
        $this->view->aMaintainFolders = $aFolders;
        $aBreadCrumb = array(
            'title' => $this->language()->translate('core.maintenance'),
            'extra_title' => '',
            'icon' => '',
            'url' => 'javascript:void(0);',
            'title_extra' => '',
            'path' => array(
                $this->url()->makeUrl('core/maintenance', array('admincp' => true)) => $this->language()->translate('core.maintenance'),
            ),
        );
        $this->template()->setBreadCrumb($aBreadCrumb);
        $aMenus = array(
        		'add-new' => array(
        				'title' => $this->language()->translate('core.flush_cache'),
        				'action' => $this->url()->makeUrl('core/maintenance',array('admincp' => true,'flush' => $aCacheInfo['adapter'])),
        				'custom' => '',
        				'class' => 'btn btn-warning flush-cache-btn'
        		)
        );
        $this->app()->setSharedData('custom-menu-header',$aMenus);
    }

    public function CleanAction()
    {
        $sPath = $this->request()->get('path');
        if (!empty($sPath))
        {
            (new Maintenance())->removeItem($sPath);
        }
        system_display_result(array(
            'message' => 'OK'
        ));
    }

    public function CalculateAction()
    {
        $sPath = $this->request()->get('path');
        $aResults = array(
            'total_size' => 'N/A',
            'total_file' => 'N/A',
        );
        if (!empty($sPath))
        {
            list($iTotalFile, $iTotalSize) = (new Maintenance())->getFolderInfo($sPath);
            $aResults['total_file'] = $iTotalFile;
            $aResults['total_size'] = Utils::file_size($iTotalSize, "mb");
        }
        system_display_result($aResults);
    }

}
