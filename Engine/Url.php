<?php

namespace App\Engine;

use APP\Engine\Object;
use APP\Engine\Utils;

class Url extends Object
{

    public function makeUrl($sURI = '', $aParams = array())
    {
        $aRouter = $this->app->router->getRouter($sURI);
        $app = \APP\Engine\Application::getInstance();
        $bAdminLink = false;
        if (isset($aParams['admincp']))
        {
        	$bAdminLink = $aParams['admincp'];
            unset($aParams['admincp']);
        }
        $sBaseUrl = $app->getBaseURL();
        if ($bAdminLink)
        {
            $sBaseUrl .= $this->app->getConfig('system', 'admin_path') . '/';
        }

        if (isset($aRouter['route']) && !empty($sURI))
        {
            $route = $aRouter['route'];
            if (preg_match_all('`(/|\.|)\[([^:\]]*+)(?::([^:\]]*+))?\](\?|)`', $route, $aMatches, PREG_SET_ORDER))
            {
                $url = $sBaseUrl . $route;
                foreach ($aMatches as $sMatch)
                {
                    list($block, $pre, $type, $param, $optional) = $sMatch;
                    if ($pre)
                    {
                        $block = substr($block, 1);
                    }
                    if (isset($aParams[$param]))
                    {
                        $url = str_replace($block, $aParams[$param], $url);
                        unset($aParams[$param]);
                    } elseif ($optional)
                    {
                        $url = str_replace($pre . $block, '', $url);
                    }
                }
                if(count($aParams))
                {
                	$url = $url .'?'.http_build_query($aParams);
                }
                return $url;
            }
        }
        if (strpos($sURI, 'http') === 0)
        {
            $sBaseUrl = $sURI;
        } else
        {
            if (empty($sURI) && !count($aParams) && !$bAdminLink)
            {
                return $app->getBaseURL();
            }
            $sBaseUrl = $sBaseUrl . $sURI;
        }
        return $sBaseUrl . (count($aParams) ? "?" . http_build_query($aParams) : "");
    }

    /**
     * Redirect to url with param and message
     * @param unknown $sURL
     * @param array $aParams
     * @param string $sMessage
     * @param string $sFlag
     */
    public function redirect($sURL, $aParams = array(), $sMessage = "", $sFlag = "success")
    {
        if (strpos($sURL, 'http') !== 0)
        {
            $sURL = $this->makeUrl($sURL, $aParams);
        }
        if (!empty($sMessage))
        {
            $this->app->flash->set($sMessage, "system", $sFlag, true);
        }
        header('Location:' . $sURL);
        exit;
    }

    public function getCurrentUrl()
    {
       return Utils::getCurrentURL();
    }

}
