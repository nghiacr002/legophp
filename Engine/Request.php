<?php

namespace APP\Engine;

class Request
{

    private $_aRequestHeaders = array();
    private $_aSegments = array();
    private $_aParams = array();
    private $_sMethod = "GET";
    public function __construct()
    {
        $this->_sMethod = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
        $this->_aRequestHeaders = $this->getHeaders();
        $this->_aSegments = $this->getSegments();
        $this->_getParams();
    }
	public function getHeader($sKey)
	{
		return isset($this->_aRequestHeaders[$sKey]) ? $this->_aRequestHeaders[$sKey] : null;
	}
    public function get($mKey, $mDefaultValue = null)
    {
        return isset($this->_aParams[$mKey]) ? $this->_aParams[$mKey] : $mDefaultValue;
    }

    public function isPost()
    {
        if ($this->_sMethod == "POST" || $_POST)
        {
            return true;
        }
        return false;
    }

    public function isAjax()
    {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == "XMLHttpRequest")
        {
            return true;
        }
        return false;
    }

    public function setParam($mKey, $mValue)
    {
        $this->_aParams[$mKey] = $mValue;
        return $this;
    }

    public function setParams($aParams)
    {
        $this->_aParams = array_merge($this->_aParams, $aParams);
        return $this;
    }

    public function getParams($bRemoveRouter = true)
    {
        $aParams = $this->_aParams;
        if (isset($aParams['router']))
        {
            unset($aParams['router']);
        }
        return $aParams;
    }

    public function seg($index)
    {
        return isset($this->_aSegments[$index]) ? $this->_aSegments[$index] : null;
    }

    public function getSegments()
    {
        if (isset($_SERVER['REQUEST_URI']))
        {
            $aSegments = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));
            return $aSegments;
        }
        return array();
    }

    public function getHeaders()
    {
        $headers = array();
        foreach ($_SERVER as $name => $value)
        {
            if (substr($name, 0, 5) == 'HTTP_')
            {
                $headers[str_replace(' ', '-', (strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }

    public function getHeaderVersion()
    {
        $sVersion = isset($this->_aRequestHeaders['version']) ? $this->_aRequestHeaders['version'] : "";
        return $sVersion;
    }

    protected function _getParams()
    {
        $this->_aParams = array_merge($_GET, $_POST, $_FILES);

        $sContent = file_get_contents("php://input");
        $aContentJSON = json_decode($sContent, true);
        if ($sContent && $aContentJSON)
        {
            $this->_aParams = array_merge($this->_aParams, $aContentJSON);
        }
        return $this->_aParams;
    }

}
