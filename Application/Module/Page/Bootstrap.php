<?php

namespace APP\Application\Module\Page;

use APP\Engine\Module\Bootstrap as ModuleBootstrap;

class Bootstrap extends ModuleBootstrap
{
	protected function initTemplate()
	{
		$aJsPhrase['page.are_you_sure_to_save_empty_page'] = $this->app->language->translate('page.are_you_sure_to_save_empty_page');
		$aJsPhrase['page.hide_on_this_page'] = $this->app->language->translate('page.hide_on_this_page');
		$aJsPhrase['page.show_on_this_page'] = $this->app->language->translate('page.show_on_this_page');
		$this->app->template->setJsPhrase($aJsPhrase);
	}
    public function getAdminMenu()
    {
        //$sAdminUrlPath = $this->app->getConfig('system','admin_path');
        $aMenu = array(
            'name' => $this->app->language->translate('page.page'),
            'url' => '#',
            'icon' => 'fa fa-globe',
            'sub' => array(
                'page/manage/index' => array(
                    'name' => $this->app->language->translate('page.manage_page'),
                    'url' => $this->app->router->url()->makeUrl('page/manage', array('admincp' => true)),
                    'icon' => '',
                ),
                'page/add/index' => array(
                    'name' => $this->app->language->translate('page.add_new'),
                    'url' => $this->app->router->url()->makeUrl('page/add', array('admincp' => true)),
                    'icon' => '',
                ),
            ),
        );
        return $aMenu;
    }

}
