<?php

namespace APP\Engine;

class Flash
{
    private $_aMessages;
    public function __construct()
    {
        $this->_aMessages = array();
        $aPrevMessage = \APP\Engine\Application::getInstance()->session->get('aFlashMessage');
        $this->clear(true);
        if (is_array($aPrevMessage) && count($aPrevMessage))
        {
            foreach ($aPrevMessage as $iKey => $aMessage)
            {
                if ($aMessage['keep'])
                {
                    $this->_aMessages[] = $aMessage;
                }
            }
        }
    }

    public function set($mMessage, $sIdElement = "system", $sType = "success", $bKeepOnRedirect = true)
    {
        if (!is_array($mMessage))
        {
            $mMessage = array($mMessage);
        }
        foreach ($mMessage as $iKey => $sMessage)
        {
            $aMessage = array(
                'message' => $sMessage,
                'type' => $sType,
                'element' => $sIdElement,
                'keep' => $bKeepOnRedirect,
            );
            ;
            $this->_aMessages[] = $aMessage;
        }
        \APP\Engine\Application::getInstance()->session->set('aFlashMessage', $this->_aMessages);
        return $this;
    }

    public function hasMessage()
    {
        return count($this->_aMessages) > 0 ? true : false;
    }

    public function getMessages()
    {
        return $this->_aMessages;
    }

    public function clear($bClearSessionCache = false)
    {
        $this->_aMessages = array();
        if ($bClearSessionCache)
        {
            \APP\Engine\Application::getInstance()->session->remove('aFlashMessage');
        }
        return $this;
    }

}

?>