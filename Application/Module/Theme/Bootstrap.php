<?php

namespace APP\Application\Module\Theme;

use APP\Engine\Module\Bootstrap as ModuleBootstrap;
class Bootstrap extends ModuleBootstrap
{
    protected function initTemplate()
    {
        $aJsPhrase['core.edit_menu'] = $this->app->language->translate('core.edit_menu');
        $aJsPhrase['core.add_new_menu'] = $this->app->language->translate('core.add_new_menu');
        $aJsPhrase['theme.edit_widget'] = $this->app->language->translate('theme.edit_widget');
        $aJsPhrase['theme.add_new_controller'] = $this->app->language->translate('theme.add_new_controller');
        $aJsPhrase['theme.you_will_lost_your_changes_are_you_sure'] = $this->app->language->translate('theme.you_will_lost_your_changes_are_you_sure');
        $this->app->template->setJsPhrase($aJsPhrase);
    }
}
