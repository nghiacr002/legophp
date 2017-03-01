<?php

namespace APP\Application\Module\Theme;

use APP\Engine\Module\Controller;
use APP\Application\Module\Theme\Model\Theme as ModelTheme;
use APP\Application\Module\Theme\Model\DbTable\DbRow\Theme;
use APP\Engine\File;

class AdminIndexController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->auth()->acl()->hasPerm('core.can_access_theme_page', true);
    }

    public function BrowseAction()
    {
        $oModelTheme = (new ModelTheme());
        $aThemes = $oModelTheme->getAll();
        $this->template()->setHeader(array(
            'bootstrap-switch.min.js' => 'module_core',
            'bootstrap-switch.min.css' => 'module_core',
            'admin_theme.js' => 'module_theme'
        ));
        $this->view->aThemes = $aThemes;
        $aBreadCrumb = array(
            'title' => $this->language()->translate('theme.themes'),
            'extra_title' => '',
            'icon' => '',
            'url' => 'javascript:void(0);',
            'title_extra' => '',
            'path' => array(
                $this->url()->makeUrl('theme/browse', array('admincp' => true)) => $this->language()->translate('core.theme'),
            ),
        );
        $this->template()->setBreadCrumb($aBreadCrumb);
    }

    public function UpdateAllAction()
    {
        $oModelTheme = (new ModelTheme());
        $aVals = $this->request()->getParams(true);
        if (isset($aVals['is_default']) && $aVals['is_default'] > 0)
        {
            $oModelTheme->setDefault($aVals['is_default']);
            if (isset($aVals['is_active']) && count($aVals['is_active']))
            {
                foreach ($aVals['is_active'] as $sValue)
                {
                    $aParts = explode('-', $sValue);
                    if (count($aParts) == 2)
                    {
                        if ($aParts[1] == 0 && $aParts[0] == $aVals['is_default'])
                        {
                            continue;
                        }
                        try
                        {
                            $oTheme = $oModelTheme->getOne($aParts[0]);
                            if ($oTheme->theme_id)
                            {
                                $oTheme->is_active = $aParts[1];
                                $oTheme->update();
                            }
                        } catch (Exception $ex)
                        {
                            
                        }
                    }
                }
            }
        }
    }
	public function EditAction()
	{
		$iId = $this->request()->get('id');
		$oModelTheme = (new ModelTheme());
		$this->view->oTheme = $oTheme = $oModelTheme->getOne($iId);
		$oFile = new File(); 
		$iFileSize = $oFile->getMaximumFileUploadSize();
		if(!$oTheme || !$oTheme->theme_id)
		{
			$this->url()->redirect('theme/browse',array('admincp' => true),$this->language()->translate('core.items_not_found'));
		}
		if($this->request()->isPost())
		{
			$aVals = $this->request()->getParams();
			if(isset($aVals['id']))
			{
				unset($aVals['id']);
			}
			if (isset($_FILES['file_logo']))
			{
				$sPath = APP_UPLOAD_PATH. "Image". APP_DS . "Logo" . APP_DS;
				$sNewFileName = $oFile->upload('file_logo', $sPath);
				unset($aVals['file_logo']);
				$aVals['logo'] = "Image". APP_DS . "Logo" . APP_DS. $sNewFileName;
			}
			
			$oTheme->mergeData($aVals);
			try
			{
				if($oTheme->update())
				{
					$this->url()->redirect('theme/edit',array('admincp' => true,'id' => $iId),$this->language()->translate('theme.updated_theme_successfully'));
				}
			}
			catch(\Exception $ex)
			{
				//
			}
		}
		$this->view->iId = $iId;
		$this->view->sFileSizeLimit = $iFileSize;
		$this->template()->setHeader(array(
			'bootstrap-switch.min.js' => 'module_core',
			'bootstrap-switch.min.css' => 'module_core',
			'admin_theme.js' => 'module_theme',
		));
	}
}
