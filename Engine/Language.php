<?php

namespace APP\Engine;

use APP\Engine\Object;

class Language extends Object
{

    private $_sCurrentLanguage = "en";
    private $_aLanguages = array();

    public function __construct($sCurrentLanguage = "en")
    {
        $this->_sCurrentLanguage = $sCurrentLanguage;
    }

    public function getCurrentLanguage()
    {
        return $this->_sCurrentLanguage;
    }

    public function translate($sPhraseVar = "", $aParams = array(), $sLanguage = "en")
    {
        return $this->get($sPhraseVar, $aParams, $sLanguage);
    }

    public function get($sPhraseVar = "", $aParams = array(), $sLanguage = "en")
    {
        if (isset($this->_aLanguages[$sLanguage][$sPhraseVar]))
        {
            if (count($aParams))
            {
                $sPhrase = $this->_aLanguages[$sLanguage][$sPhraseVar];
                foreach ($aParams as $sKey => $aParam)
                {
                    if (is_array($aParam))
                    {
                        continue;
                    }
                    $aFind[] = '{' . $sKey . '}';
                    $aReplace[] = '' . $aParam . '';
                }
                $sPhrase = str_replace($aFind, $aReplace, $sPhrase);
                return $sPhrase;
            }
            return $this->_aLanguages[$sLanguage][$sPhraseVar];
        }
        return $sPhraseVar;
    }

    public function appendPhrases($aPhrases, $sLanguage = null)
    {
        if (!$sLanguage)
        {
            $sLanguage = $this->getCurrentLanguage();
        }
        if (!isset($this->_aLanguages[$sLanguage]))
        {
            $this->_aLanguages[$sLanguage] = array();
        }
        $this->_aLanguages[$sLanguage] = array_merge($this->_aLanguages[$sLanguage], $aPhrases);
        return $this;
    }
}

?>
