<?php

namespace APP\Engine;

class Minify
{

    private $_iLimitFileSize = 512000; //512kb

    public function __construct()
    {
        $this->_iLimitFileSize = \APP\Engine\Application::getInstance()->getConfig('minify', 'file_size') * 1024;
    }

    /**
     * Compress js file
     * 
     * @param mixed $aFiles
     * @return mixed
     */
    public function minJS($aFiles = array(), $sCacheId = "")
    {
        return $this->minData($aFiles, $sCacheId, "js");
    }

    /**
     * Compress Css file
     * 
     * @param mixed $aFiles
     * @return mixed
     */
    public function minCSS($aFiles = array(), $sCacheId = "")
    {
        return $this->minData($aFiles, $sCacheId, "css");
    }

    private function minData($aFiles = array(), $sCacheId = "", $sType = "css")
    {
        if (!count($aFiles))
        {
            return array();
        }
        $iCnt = 1;
        $sFilePath = APP_PUBLIC_PATH . "Minify" . APP_DS;
        $sBaseURL = Application::getInstance()->getBaseURL();
        $sFileName = $sCacheId . $iCnt . '.' . $sType;
        $sURL = $sBaseURL . 'Public/Minify/' . $sCacheId . $iCnt . '.' . $sType;
        $iFileSplitSize = 0;
        $sContent = "";
        $aResults = array();

        foreach ($aFiles as $iKey => $sFile)
        {
            if (!file_exists($sFile))
            {
                continue;
            }
            $iFileSize = filesize($sFile);
            if ($iFileSize <= 0)
            {
                continue;
            }
            $iFileSplitSize += $iFileSize;
            if ($iFileSplitSize > $this->_iLimitFileSize)
            {
                $oHandle = fopen($sFilePath . $sFileName, "w");
                fwrite($oHandle, $sContent);
                fclose($oHandle);
                $aResults[$sFileName] = $sURL;
                $iFileSplitSize = 0;
                $iCnt++;
                $sContent = "";
                $sFileName = $sCacheId . $iCnt . '.' . $sType;
                $sURL = $sBaseURL . 'Public/Minify/' . $sCacheId . $iCnt . '.' . $sType;
            }

            $oHandle = fopen($sFile, "r");
            $sTmpContent = fread($oHandle, $iFileSize);
            fclose($oHandle);
            $sTmpContent = $this->rebuildImage($sType, $iKey, $sTmpContent);
            $sTmpContent = $this->compress($sTmpContent, $sType);
            $sContent .= $sTmpContent;
        }
        if ($iFileSplitSize <= $this->_iLimitFileSize)
        {
            $oHandle = fopen($sFilePath . $sFileName, "w");
            fwrite($oHandle, $sContent);
            fclose($oHandle);
            $aResults[$sFileName] = $sURL;
        }

        return $aResults;
    }

    private function rebuildImage($sType, $sURL, $sFileContent = "")
    {
        if ($sType != "css")
        {
            return $sFileContent;
        }

        preg_match_all('#url\((.*?)\)#', $sFileContent, $aMatches);

        if (isset($aMatches[1]) && count($aMatches[1]) > 0)
        {
            $iLastSlash = strrpos($sURL, "/");
            $sURL = substr($sURL, 0, $iLastSlash);

            foreach ($aMatches[1] as $sLink)
            {

                if (strpos($sLink, 'http') === 0)
                {
                    continue;
                }
                $iTmpLastSlash = $iLastSlash;
                $sTmpURL = $sURL;
                $aParts = explode('..', $sLink);
                $sLink = str_replace('"', '', $sLink);
                $sLink = str_replace('\'', '', $sLink);
                $sImageLink = $sLink;
                if (count($aParts) > 1 && is_array($aParts))
                {
                    for ($i = 0; $i < count($aParts); $i++)
                    {
                        $sTmpURL = substr($sTmpURL, 0, $iTmpLastSlash);
                        $iTmpLastSlash = strrpos($sTmpURL, "/");
                    }
                    $sN = $aParts[count($aParts) - 1];
                    $sN = str_replace('\'', '', $sN);
                    $sN = str_replace('"', '', $sN);
                    $sImageLink = $sN;
                } elseif (strpos($sLink, "/") !== 0)
                {

                    $sImageLink = '/' . $sImageLink;
                }
                $sImageLink = $sTmpURL . $sImageLink;

                $sFileContent = str_replace($sLink, $sImageLink, $sFileContent);
            }
        }
        return $sFileContent;
    }

    private function compress($sContent, $sType)
    {
        if ($sType == "css")
        {
            return $this->compressCSS($sContent);
        }
        return $this->compressJS($sContent);
    }

    /**
     * Min HTML
     * 
     * @param mixed $sContent
     * @return mixed
     */
    public function minHTML($sContent = "")
    {
        $sContent = $this->removeComments($sContent);
        //d($sContent);die();
        $sContent = str_replace("\r\n", "", $sContent);
        $sContent = str_replace("\n", "", $sContent);
        $sContent = str_replace("\r", "", $sContent);
        $sContent = preg_replace('[\r\n]', '', $sContent);
        $aSearch = array(
            '/\>[^\S ]+/s',
            '/[^\S ]+\</s',
            '/(\s)+/s',
        );
        $aReplace = array(
            '>',
            '<',
            '\\1',
        );
        $sContent = preg_replace($aSearch, $aReplace, $sContent);
        return $sContent;
    }

    public function compressCSS($sContent)
    {
        /* remove comments */
        //$sContent = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $sContent);
        $sContent = $this->removeComments($sContent);
        /* remove tabs, spaces, newlines, etc. */
        $sContent = str_replace("\r\n", "", $sContent);
        $sContent = str_replace("\n", "", $sContent);
        $sContent = str_replace("\r", "", $sContent);
        $sContent = preg_replace('[\r\n]', '', $sContent);
        /* remove other spaces before/after ; */
        $sContent = preg_replace(array('(( )+{)', '({( )+)'), '{', $sContent);
        $sContent = preg_replace(array('(( )+})', '(}( )+)', '(;( )*})'), '}', $sContent);
        $sContent = preg_replace(array('(;( )+)', '(( )+;)'), ';', $sContent);
        return $sContent;
    }

    public function compressJS($sContent)
    {
        $sContent = $this->removeComments($sContent);
        $sContent = preg_replace('/(?<!\S)\/\/\s*[^\r\n]*/', '', $sContent);
        $sContent = preg_replace('[\r\n]', '', $sContent);
        $sContent = str_replace("\r\n", "", $sContent);
        $sContent = str_replace("{\n", "{", $sContent);
        $sContent = str_replace(",\n", ",", $sContent);
        //$sContent = preg_replace(array('(( )+{)','({( )+)'), '{', $sContent);
        //$sContent = preg_replace(array('(( )+})','(}( )+)','(;( )*})'), '}', $sContent);
        $sContent = preg_replace(array('(;( )+)', '(( )+;)'), ';', $sContent);
        return $sContent;
    }

    public function gzip($aFiles)
    {
        $sFilePath = APP_PUBLIC_PATH . 'Minify' . APP_DS;

        $sContent = "";
        foreach ($aFiles as $iKey => $sFile)
        {
            $sFullFilePath = $sFilePath . $sFile;
            if (!file_exists($sFullFilePath))
            {
                continue;
            }
            $iFileSize = filesize($sFullFilePath);
            if ($iFileSize <= 0)
            {
                continue;
            }
            $oHandle = fopen($sFullFilePath, "r");
            $sTmpContent = fread($oHandle, $iFileSize);
            fclose($oHandle);
            $sContent .= $sTmpContent;
        }
        return $sContent;
    }

    public function removeComments($sContent)
    {
        $sContent = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', ' ', $sContent);
        $sContent = preg_replace('/(\s+)\/\*([^\/]*)\*\/(\s+)/s', "\n", $sContent);
        $sContent = preg_replace('#^\s*//.+$#m', "", $sContent);
        return $sContent;
    }

}

?>