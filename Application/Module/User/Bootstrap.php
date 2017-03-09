<?php

namespace APP\Application\Module\User;

use APP\Engine\Module\Bootstrap as ModuleBootstrap;
use APP\Application\Module\User\Model\User as UserModel;

class Bootstrap extends ModuleBootstrap
{

    protected function initTemplate()
    {
        $aJsPhrase['theme.add_new_html_widget'] = $this->app->language->translate('theme.add_new_html_widget');
        $aJsPhrase['user.user_name_contain_illegal_characters'] = $this->app->language->translate('user.user_name_contain_illegal_characters');
        $aJsPhrase['user.user_name_cannot_be_empty'] = $this->app->language->translate('user.user_name_cannot_be_empty');
        $aJsPhrase['theme.user_name_wrong_length'] = $this->app->language->translate('user.user_name_wrong_length');
        $this->app->template->setJsPhrase($aJsPhrase);
    }

    public function getAdminMenu()
    {
        $oUrl = $this->app->router->url();
        $aMenu = array(
            'name' => $this->app->language->translate('user.user'),
            'url' => 'javascript:void(0);',
            'icon' => 'fa fa-users',
            'sub' => array(
                'user/browse' => array(
                    'name' => $this->app->language->translate('user.manage_user'),
                    'url' => $oUrl->makeUrl('user/browse', array('admincp' => true)),
                    'icon' => '',
                ),
                'user/group' => array(
                    'name' => $this->app->language->translate('user.manage_group'),
                    'url' => $oUrl->makeUrl('user/group', array('admincp' => true)),
                    'icon' => '',
                ),
                'user/add' => array(
                    'name' => $this->app->language->translate('user.add_new_user'),
                    'url' => $oUrl->makeUrl('user/add', array('admincp' => true)),
                    'icon' => '',
                ),
                'user/group/add' => array(
                    'name' => $this->app->language->translate('user.add_new_group'),
                    'url' => $oUrl->makeUrl('user/group/add', array('admincp' => true)),
                    'icon' => '',
                ),
            ),
        );
        return $aMenu;
    }

    public function getStatistic()
    {
        $iTotal = (new UserModel())->getTotal();
        return array(
            'user' => array(
                'text' => $this->app->language->translate('user.total_user'),
                'value' => $iTotal,
            )
        );
    }
    protected function subscribeEvent()
    {
    	$this->app()->event->subscribe("onDeleteBlog",array((new UserModel()),'onDeleteBlog'));
    }
}
