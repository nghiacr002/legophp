<?php

namespace APP\Library;

class FlexRouter extends \AltoRouter
{
	public function getBasePath()
	{
		return $this->basePath;
	}
    public function match($requestUrl = null, $requestMethod = null)
    {

        $params = array();
        $match = false;

        // set Request Url if it isn't passed as parameter
        if ($requestUrl === null)
        {
            $requestUrl = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
        }

        // strip base path from request url
        $requestUrl = substr($requestUrl, strlen($this->basePath));

        // Strip query string (?a=b) from Request Url
        if (($strpos = strpos($requestUrl, '?')) !== false)
        {
            $requestUrl = substr($requestUrl, 0, $strpos);
        }

        // set Request Method if it isn't passed as a parameter
        if ($requestMethod === null)
        {
            $requestMethod = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
        }

        foreach ($this->routes as $handler)
        {
            list($methods, $route, $target, $name) = $handler;

            $method_match = (stripos($methods, $requestMethod) !== false);

            // Method did not match, continue to next route.
            if (!$method_match)
                continue;

            if ($route === '*')
            {
                // * wildcard (matches all)
                $match = true;
            } elseif (isset($route[0]) && $route[0] === '@')
            {
                // @ regex delimiter
                $pattern = '`' . substr($route, 1) . '`u';
                $match = preg_match($pattern, $requestUrl, $params) === 1;
            } elseif (($position = strpos($route, '[')) === false)
            {
                // No params in url, do string comparison
                $match = strcmp($requestUrl, $route) === 0;
            } else
            {
                // Compare longest non-param string with url
                if (strncmp($requestUrl, $route, $position) !== 0)
                {
                    continue;
                }
                $regex = $this->compileRoute($route);
                $match = preg_match($regex, $requestUrl, $params) === 1;
            }

            if ($match)
            {
                if ($params)
                {
                    foreach ($params as $key => $value)
                    {
                        if (is_numeric($key))
                            unset($params[$key]);
                    }
                }
                $bReturn = true;
                if (function_exists("validate_" . $name))
                {
                    $bReturn = call_user_func_array("validate_" . $name, $params);
                }
                if ($bReturn)
                {
                    return array(
                        'target' => $target,
                        'params' => $params,
                        'name' => $name
                    );
                }
            }
        }

        //no router found,parse default
        $segs = explode('/', $requestUrl);
        if (is_array($segs) && !empty($segs[0]))
        {
            list($module, $controller, $action) = array("core", "index", "index");
            if (isset($segs[0]))
            {
                $module = $segs[0];
            }
            if (isset($segs[1]) && !empty($segs[1]))
            {
                $controller = $segs[1];
            }
            if (isset($segs[2]) && !empty($segs[2]))
            {
                $action = $segs[2];
            }
        } elseif (!$requestUrl)
        {
            $module = "Core";
            $controller = "Index";
            $action = "Index";
        } else
        {
            return false;
        }
        return array(
            'name' => 'app_parsing_default',
            'module' => $module,
            'controller' => $controller,
            'action' => $action,
            'route' => $module . '/' . $controller . '/' . $action,
            'target' => null,
            'params' => array(),
        );
    }

    private function compileRoute($route)
    {
        if (preg_match_all('`(/|\.|)\[([^:\]]*+)(?::([^:\]]*+))?\](\?|)`', $route, $matches, PREG_SET_ORDER))
        {

            $matchTypes = $this->matchTypes;
            foreach ($matches as $match)
            {
                list($block, $pre, $type, $param, $optional) = $match;

                if (isset($matchTypes[$type]))
                {
                    $type = $matchTypes[$type];
                }
                if ($pre === '.')
                {
                    $pre = '\.';
                }

                //Older versions of PCRE require the 'P' in (?P<named>)
                $pattern = '(?:'
                        . ($pre !== '' ? $pre : null)
                        . '('
                        . ($param !== '' ? "?P<$param>" : null)
                        . $type
                        . '))'
                        . ($optional !== '' ? '?' : null);

                $route = str_replace($block, $pattern, $route);
            }
        }
        return "`^$route$`u";
    }

}
