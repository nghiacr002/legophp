<?php

namespace APP\Application\Module\Core;

use APP\Engine\Module\Bootstrap as ModuleBootstrap;
use APP\Application\Module\Core\Plugin\Twig_Core_Extension;
use APP\Application\Module\Core\Model\LanguagePatch as LanguagePatch;

class Bootstrap extends ModuleBootstrap
{

    protected function initTemplate()
    {
        $this->app->template->addExtension(new Twig_Core_Extension());
        $this->app->template->addExtension(new \Twig_Extension_Debug());
        $this->app->template->setHeader(array(
            'bootbox.min.js' => 'module_core',
            'core.js' => 'module_core'
        ));
        $aCoreParams = array(
            'sBaseUrl' => $this->app->getBaseURL(),
            'sBaseAdminUrl' => $this->app->router->url()->makeUrl('', array('admincp' => true)),
            'sAppVersion' => $this->app->getVersion(),
            'sAppName' => $this->app->getName(),
            'bIsDebug' => $this->app->isDebug(),
        );
        $aJsPhrase['core.yes'] = $this->app->language->translate('core.yes');
        $aJsPhrase['core.no'] = $this->app->language->translate('core.no');
        $aJsPhrase['core.edit'] = $this->app->language->translate('core.edit');
        $aJsPhrase['core.delete'] = $this->app->language->translate('core.delete');
        $aJsPhrase['core.are_you_sure'] = $this->app->language->translate('core.are_you_sure');
        $this->app->template->setJsPhrase($aJsPhrase);
        $this->app->template->setJsParams($aCoreParams);
        if ($this->app->auth->isAuthenticated())
        {
            $this->app->template->assign(array(
                'aCurrentUser' => $this->app->auth->getViewer()
            ));
        }
    }
	protected function initLanguage()
	{
		parent::initLanguage();
		$sCurrentLanguage = $this->app->language->getCurrentLanguage();
		$aConds = array(
			array('language_code',$sCurrentLanguage)
		);
		$aPatchLanguages = (new LanguagePatch())->getAllWithCache($aConds, null, null);
		if(count($aPatchLanguages))
		{
			$this->app->language->appendPhrases($aPatchLanguages[$sCurrentLanguage],$sCurrentLanguage);
		}
	}
    public function getAdminMenu()
    {
        return false;
        //$sAdminUrlPath = $this->app->getConfig('system','admin_path');
        $aMenu = array(
            'name' => $this->app->language->translate('core.core'),
            'url' => '#',
            'icon' => 'fa fa-globe',
            'sub' => array(
                'core/country/index' => array(
                    'name' => $this->app->language->translate('core.country'),
                    'url' => $this->app->router->url()->makeUrl('core/country', array('admincp' => true)),
                    'icon' => '',
                ),
            ),
        );
        return $aMenu;
    }


}
