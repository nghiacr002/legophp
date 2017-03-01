<?php

namespace APP\Application\Module\Core\Plugin;

use APP\Engine\Module\Plugin;

class Twig_Core_Extension extends \Twig_Extension
{

    public function getName()
    {
        return 'Twig_Core_Extension';
    }

    public function getFilters()
    {
        return array(
            'file_size' => new \Twig_SimpleFilter('file_size', '\APP\Engine\Utils::file_size'),
            'date_format' => new \Twig_SimpleFilter('date_format', '\APP\Engine\Utils::date_format'),
            'image_path' => new \Twig_SimpleFilter('image_path', '\APP\Engine\Utils::image_path'),
            'is_array' => new \Twig_SimpleFilter('is_array', 'is_array'),
            'unserialize' => new \Twig_SimpleFilter('unserialize', 'unserialize'),
        );
    }

    public function getFunctions()
    {
        return array(
            'Twig_App_Widget' => new \Twig_SimpleFunction('Twig_App_Widget', array($this, 'Twig_App_Widget_Parse'), array('is_safe' => array('html'))),
            'Twig_App_Debug_Output' => new \Twig_SimpleFunction('Twig_App_Debug_Output', array($this, 'Twig_App_Debug_Output'), array('is_safe' => array('html'), 'is_safe_callback' => true)),
            'Template_*' => new \Twig_SimpleFunction("Template_*", array($this, 'Twig_Template_Data'), array('is_safe' => array('html'))),
            'Translate' => new \Twig_SimpleFunction("Translate", array($this, 'Twig_Template_Translate'), array('is_safe' => array('html'))),
            '_TL' => new \Twig_SimpleFunction("_TL", array($this, 'Twig_Template_Translate'), array('is_safe' => array('html'))),
            'App_' => new \Twig_SimpleFunction("App_*", array($this, 'Twig_App_Core_Function'), array('is_safe' => array('html'))),
            'Location' => new \Twig_SimpleFunction("Location", array($this, 'Twig_App_Theme_Location'), array('is_safe' => array('html'))),
        );
    }

    public function Twig_App_Debug_Output()
    {
        return $this->Twig_App_Widget_Parse('core/debug', array());
    }

    public function Twig_Template_Translate($sName, $aParams = array())
    {
        return \APP\Engine\Application::getInstance()->language->get($sName, $aParams);
    }

    public function Twig_App_Core_Function()
    {
        $app = \APP\Engine\Application::getInstance();
        $aParams = func_get_args();
        $sName = isset($aParams[0]) ? $aParams[0] : "";
        if (count($aParams))
        {
            array_shift($aParams);
        }
        $sMethodName = "get" . ucfirst($sName);
        if (method_exists($app, $sMethodName))
        {
            return call_user_func_array(array($app, $sMethodName), $aParams);
        }
        return "";
    }

    public function Twig_App_Theme_Location()
    {
        $app = \APP\Engine\Application::getInstance();
        $bDesignMode = $app->getSharedParam('design-mode');
        $Args = func_get_args();
        $aParams = array();
        if (count($Args))
        {
            $aParams['location_id'] = $Args[0];
        }
        $aParams['bDesignMode'] = $bDesignMode;
        return $this->Twig_App_Widget_Parse('theme/location', $aParams);
    }

    public function Twig_App_Widget_Parse($sName, $aParams = array())
    {
    	($sPluginCode = Plugin::fetch('Twig_App_Widget_Parse',true)) ? @eval($sPluginCode) : false;
        $aParts = explode("/", $sName);
        if (count($aParts) == 2)
        {
            $sModule = ucfirst($aParts[0]);
            $sWidgetName = ucfirst($aParts[1]);
            $oWidget = \APP\Engine\Module\Component::factory($sModule, $sWidgetName, "Widget");
            if ($oWidget && method_exists($oWidget, "process"))
            {
                $oWidget->setParams($aParams);
                $bResult = $oWidget->process();
                if ($bResult !== false)
                {
                    return $oWidget->getContent($sWidgetName);
                }
            }
        }
        return null;
    }

    public function Twig_Template_Data()
    {
        $aParams = func_get_args();
        $sName = "";
        if (count($aParams))
        {
            $sName = $aParams[0];
            array_shift($aParams);
        }
        $oTemplate = \APP\Engine\Template::getInstance();
        $sMethodName = "get" . ucfirst($sName);
        if (method_exists($oTemplate, $sMethodName))
        {
            return call_user_func_array(array($oTemplate, $sMethodName), $aParams);
        }
        return "";
    }


}
