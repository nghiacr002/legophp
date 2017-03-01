<?php

namespace APP\Engine;

class Response
{

    private $_sCode;
    private $_sMessage;
    private $_aParams;
    private $_sContentType;
    private $_bIsCacheRequest;
    private $_sEtag;
    private $_sExpiredTime;
    private $_sLastModify;

    public function __construct($sCode = "", $sMessage = "", $sContentType = "", $aParams = array())
    {
        $this->_sCode = $sCode;
        $this->_sMessage = $sMessage;
        $this->_sContentType = $sContentType;
        $this->_aParams = $aParams;
        $this->_bIsCacheRequest = false;
    }

    public function setLastModify($sTime)
    {
        $this->_sLastModify = $sTime;
        return $this;
    }

    public function getLastModify()
    {
        return $this->_sLastModify;
    }

    public function setExpiredTime($sTime)
    {
        $this->_sExpiredTime = $sTime;
        return $this;
    }

    public function getExpiredTime()
    {
        return $this->_sExpiredTime;
    }

    public function setETag($sTag)
    {
        $this->_sEtag = $sTag;
        return $this;
    }

    public function getETag()
    {
        return $this->_sEtag;
    }

    public function display($format = 'JSON', $bExit = true)
    {
        $aReturn = $this->_aParams;
        if (!empty($this->_sMessage))
        {
            $aReturn['message'] = $this->_sMessage;
        }
        ob_clean();
        $sContentType = "";
        switch ($format)
        {
            case 'JSON':
                $sContentType = "application/json";
                echo json_encode($aReturn);
                break;
            case 'XML':
                $sContentType = "application/xml";
                echo $this->displayXML($aReturn);
                break;
            case 'RAW':
                echo var_export($aReturn, true);
            default:
                break;
        }
        if ($bExit)
        {
            if (!empty($this->_sEtag))
            {
                header("ETag:" . $this->_sEtag);
            }
            if (!empty($this->_sLastModify))
            {
                header("Last-Modified:" . $this->_sLastModify);
            }
            if (!empty($this->_sExpiredTime))
            {
                header("Expires:" . $this->_sExpiredTime);
            }
            if (!empty($this->_sContentType))
            {
                header('Content-Type:' . $this->_sContentType);
            } else
            {
                header('Content-Type:' . $sContentType);
            }
            http_response_code($this->_sCode);
            exit;
        }
    }

    public function setContentType($sContentType)
    {
        $this->_sContentType = $sContentType;
        return $this;
    }

    public function getContentType()
    {
        return $this->_sContentType;
    }

    public function displayXML($aReturn)
    {
        return array_to_xml($aReturn);
    }

    public function setCode($sCode)
    {
        $this->_sCode = $sCode;
        return $this;
    }

    public function setMessage($sMessage)
    {
        $this->_sMessage = $sMessage;
        return $this;
    }

    public function setParams($aParams = array())
    {
        $this->_aParams = $aParams;
        return $this;
    }

    public function getCode()
    {
        return $this->_sCode;
    }

    public function getMessage()
    {
        return $this->_sMessage;
    }

}
