<?php

namespace APP\Application\Module\Core;

use APP\Engine\Module\Bootstrap as ModuleBootstrap;
use APP\Application\Module\Core\Plugin\Twig_Core_Extension;
use APP\Application\Module\Core\Model\LanguagePatch as LanguagePatch;
use APP\Application\Module\Core\Model\Category as CategoryModel;
use APP\Library\Sitemap;
use APP\Application\Module\Theme\Model\Menu;

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
        $sCodeGA = $this->app->getSetting('seo.google_analysis_code');
        if(!empty($sCodeGA))
        {
        	if(strpos($sCodeGA, '<script>') === false)
        	{
        		$sCodeGA = '<script>'.$sCodeGA.'</script>';
        	}
        	$this->app->template->setHeader(array(
        		$sCodeGA
        	));
        }
		$this->app->template->setHeader(array(
			'<link rel="icon" type="image/png" href="favicon.png">'
		));
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
        /*$aMenu = array(
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
        return $aMenu;*/
    }
    public function getCustomMenuItems()
    {
		$aCategories = (new CategoryModel())->getAll(array(),null,null,'*',array('category_type','DESC'));
		$aItems = array();
		if(is_array($aCategories) && count($aCategories))
		{
			foreach($aCategories as $oCategory)
			{
				$aItem = array(
					'menu_name' => $oCategory->category_name,
					'url' => $oCategory->href(),
					'type' => $oCategory->category_type
				);
				$aItems[] = $aItem;
			}
		}
		return $aItems;
    }
	public function buildSitemap($aParams = array())
	{
		//build static content only
		$oSiteMapXML = new Sitemap();
		$oSiteMapXML->start('static.xml');
		$oURL = $this->app->router->url();
		$sDate = isset($aParams['day']) ? $aParams['day'] : date('Y-m-d');
		$aMenus = (new Menu())->getMenusByType('main_menu');
		if(count($aMenus))
		{
			foreach($aMenus as $iKey => $aMenu)
			{
				$oSiteMapXML->addItem(array(
					'loc' => $oURL->makeUrl($aMenu['url']),
					'lastmod' => $sDate,
				));
			}
		}
		$oSiteMapXML->end();
		$oSiteMapXML->write();
		/**
		 * Should return index map sitemap
		 */
		return array(
			'loc' => $oURL->makeUrl('Public/Sitemap/static.xml'),
			'lastmod' => $sDate,
		);
	}
}
