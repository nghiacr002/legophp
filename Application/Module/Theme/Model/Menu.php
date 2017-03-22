<?php

namespace APP\Application\Module\Theme\Model;

use APP\Application\Module\Theme\Model\DbTable\Menu as DbMenu;
use APP\Engine\Application;
use APP\Engine\Module\Model;
use APP\Application\Module\Core\Model\Setting;
use APP\Engine\Module\Plugin;

class Menu extends Model
{

    const ACTIVATED = 1;

    public function __construct()
    {
        $this->_oTable = new DbMenu();
        parent::__construct();
    }

    public function getAdminMenus()
    {
        $aMenus = array();
        $aMenus['module'] = $this->app->module->getAdminMenus();
        $aGroup = (new Setting())->getGroupSettings();
        $aSettingMenus = array();
        foreach ($aGroup as $iKey => $sGroup)
        {
            $aSettingMenus[] = array(
                'name' => $this->language()->translate('core.' . strtolower($sGroup) . '_menu'),
                'url' => $this->url()->makeUrl('core/setting', array('admincp' => true, 'type' => $sGroup)),
                'icon' => "fa fa-cog"
            );
        }
        $aMenus['setting'] = $aSettingMenus;
        $aMenus['template'] = array(
            array(
                'name' => $this->language()->translate('core.theme'),
                'url' => $this->url()->makeUrl('theme/browse', array('admincp' => true)),
                'icon' => "fa fa-eye"
            ),
            array(
                'name' => $this->language()->translate('core.menu'),
                'url' => $this->url()->makeUrl('theme/menu', array('admincp' => true)),
                'icon' => "fa fa-list"
            ),

            array(
                'name' => $this->language()->translate('theme.layout'),
                'url' => '#',
                'icon' => "fa fa-list",
            	'sub' => array(
            			'theme/layout' => array(
            					'name' => $this->language()->translate('theme.manage_layout'),
            					'url' =>$this->url()->makeUrl('theme/layout', array('admincp' => true)),
            					'icon' => '',
            			),
            			'theme/layout/add' => array(
            					'name' => $this->app->language->translate('theme.add_new_layout'),
            					'url' => $this->app->router->url()->makeUrl('theme/layout/add', array('admincp' => true)),
            					'icon' => '',
            			),
            	)
            ),
        	array(
        		'name' => $this->language()->translate('theme.controller'),
        		'url' => $this->url()->makeUrl('theme/controller', array('admincp' => true)),
        		'icon' => "fa fa-list"
        	),
        );
        $aMenus['system'] = array(
            array(
                'name' => $this->language()->translate('core.modules'),
                'url' => $this->url()->makeUrl('core/module', array('admincp' => true)),
                'icon' => "fa fa-cube"
            ),
            array(
                'name' => $this->language()->translate('core.media'),
                'url' => $this->url()->makeUrl('core/media', array('admincp' => true)),
                'icon' => "fa fa-cube"
            ),
            array(
                'name' => $this->language()->translate('core.language_packages'),
                'url' => $this->url()->makeUrl('core/language', array('admincp' => true)),
                'icon' => "fa fa-language"
            ),
            array(
                'name' => $this->language()->translate('core.maintenance'),
                'url' => $this->url()->makeUrl('core/maintenance', array('admincp' => true)),
                'icon' => "fa fa-eraser"
            )
        );
        @eval(Plugin::fetch('menu_admin_init', true));
        $aMenus = $this->getActivatedMenu($aMenus);
        return $aMenus;
    }

    public function getActivatedMenu($aSystemMenus)
    {
        $sActiveMenu = $this->app->getSharedParam('active-menu');
        if ($sActiveMenu)
        {
            $sRouter = $sActiveMenu;
        } else
        {
            $aRouter = $this->app->module->getCurrentRouter();
            $sRouter = $aRouter['route'];
        }
        $sUrl = $this->url()->getCurrentUrl();
        foreach ($aSystemMenus as $iKey0 => $aMenus)
        {
            $bIsActive = false;
            foreach ($aMenus as $iKey => $aMenu)
            {
                if (isset($aMenu['sub']) && count($aMenu['sub']))
                {
                    foreach ($aMenu['sub'] as $iKey2 => $aSubMenu)
                    {
                        if (strpos($sRouter, $iKey2) !== false || strpos($sUrl, $aSubMenu['url']) !== false)
                        {
                            $aMenus[$iKey]['sub'][$iKey2]['active'] = true;
                            $aMenus[$iKey]['active'] = true;
                            $bIsActive = true;
                            break;
                        }
                    }
                } else
                {
                    if (strpos($sRouter, $iKey) !== false || strpos($sUrl, $aMenu['url']) !== false)
                    {
                        $aMenus[$iKey]['active'] = true;
                        $bIsActive = true;
                    }
                }
                if ($bIsActive)
                {
                    break;
                }
            }
            if ($bIsActive)
            {
                $aSystemMenus[$iKey0] = $aMenus;
            }
        }
        return $aSystemMenus;
    }

    public function getMenusByType($sType = "main_menu", $iParentId = 0, $bIsEditMode = false)
    {
        $aConds = array(
            'and-1' => array(
                'menu_type', $sType
            ),
           /* 'and-2' => array(
                'is_active', self::ACTIVATED
            )*/
        );
        if ($bIsEditMode)
        {
            unset($aConds['and-2']);
        }
        $aConds['and-3'] = array(
            'parent_id', (int) $iParentId
        );
        $aMenus = array();
        $aRows = $this->getAll($aConds, null, null, '*', array('ordering', 'ASC'));
        if (count($aRows))
        {
            foreach ($aRows as $iKey => $oRow)
            {
                $aRow = $oRow->getProps();
                if ($aRow['parent_id'] == 0)
                {
                    $aRow['sub'] = $this->getMenusByType($sType, $aRow['menu_id'], $bIsEditMode);
                }
                $aMenus[$aRow['menu_id']] = $aRow;
            }
        }
        return $aMenus;
    }

    public function getAll($aConds = array(), $iPage = null, $iLimit = null, $sSelectFields = "*", $mOrder = null)
    {
        $sCacheName = $this->_oTable->getTableName();
        $sCacheName = $sCacheName . md5($sSelectFields . md5(serialize($mOrder)) . $iPage . $iLimit . md5(serialize($aConds)));
        if ($aMenus = $this->cache()->get($sCacheName,"Model"))
        {
            return $aMenus;
        }
        $aMenus = parent::getAll($aConds, $iPage, $iLimit, $sSelectFields, $mOrder);

        if ($aMenus)
        {
            $this->cache()->set($sCacheName, $aMenus, $this->getTTL(), "Model");
        }
        return $aMenus;
    }

}
