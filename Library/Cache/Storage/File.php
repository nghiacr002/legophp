<?php

namespace APP\Library\Cache\Storage;

use APP\Engine\Object;

class File extends Object implements \APP\Library\Cache\Storage
{

    protected $_sCachePath = "";

    public function __construct()
    {
        $this->_sCachePath = \APP\Engine\Application::getInstance()->getConfig('cache', 'path');
    }

    protected function _getCacheId($sFileName)
    {
    	if(!is_string($sFileName))
    	{
    		return md5($sFileName);
    	}
        return $sFileName;
    }
	public function getFolder($sFolder = "")
	{
		if(empty($sFolder))
		{
			$sFolder = "System";
		}
		return ucfirst($sFolder);
	}
    public function get($sFileName, $sFolder = "")
    {
    	if(defined('APP_NO_CACHE') && APP_NO_CACHE)
    	{
    		return null;
    	}
		$sFolder = $this->getFolder($sFolder);
        $sRealFile = $this->_sCachePath . $sFolder. APP_DS . $this->_getCacheId($sFileName) . '.php';

        if (file_exists($sRealFile))
        {
            require($sRealFile);
            if (isset($aCacheData))
            {
                if (isset($TTL) && $TTL > 0)
                {
                    if ($TTL < APP_TIME)
                    {
                        return false;
                    }
                }
                return $aCacheData;
            } else
            {
                return false;
            }
        }
        return null;
    }

    public function set($sFileName, $mContent = array(), $iTimeToLive = 0, $sFolder = "")
    {
        $sPathStorage = $this->_sCachePath;
        $sFolder = $this->getFolder($sFolder);
        $sPathStorage = $sPathStorage . $sFolder . APP_DS;

        if (!file_exists($sPathStorage))
        {
            @mkdir($sPathStorage);
        }
        $sRealFile = $sPathStorage . $this->_getCacheId($sFileName) . '.php';

        if ($iTimeToLive > 0)
        {
            $iTime = APP_TIME + $iTimeToLive;
        } else
        {
            $iTime = -1;
        }
        try
        {
        	$sData = var_export($mContent, true);
            $sContent = "<?php \$aCacheData = " . $sData . "; \$TTL = " . $iTime . ";\n?>";
            $oFile = @fopen($sRealFile, 'w+');
            @fwrite($oFile, $sContent);
            @fclose($oFile);
        } catch (\AppException $ex)
        {

        }
        return true;
    }

    public function remove($sFileName, $sFolder = "")
    {
    	$sFolder = $this->getFolder($sFolder);
        $sRealFile = $this->_sCachePath . $sFolder. APP_DS . $this->_getCacheId($sFileName) . '.php';
        if (file_exists($sRealFile))
        {
            @unlink($sRealFile);
        }
        return $this;
    }

    public function clean($sType = "", $sPrefix = "")
    {
        $sFolder = $this->_sCachePath . $sType . APP_DS;
        if (is_dir($sFolder))
        {
            $aFiles = (new \APP\Engine\File())->scanFolder($sFolder);

            if (count($aFiles))
            {
                foreach ($aFiles as $sFileName)
                {
                	$bAllowDelete =true;
                	if(!empty($sPrefix))
                	{
                		$bAllowDelete = false;
                	}
                	if(!$bAllowDelete && strpos($sFileName,$sPrefix) === 0)
                	{
                		$bAllowDelete = true;
                	}
                	if($bAllowDelete)
                	{
                		$this->remove($sFileName);
                	}

                }
            }
        }
    }
    public function flush()
    {
    	return $this->clean();
    }
    public function getCaches($sType = "")
    {
        $sDir = $this->_sCachePath . $sType . APP_DS;
        $aFolders = scandir($sDir);
        $aResult = array();
        $aOthers = array(
            'total' => 0,
            'size' => 0,
            'last' => 0
        );
        foreach ($aFolders as $iKey => $mData)
        {
            $sPath = $sDir . $mData;
            if (is_file($sPath))
            {
                $aOthers['total'] ++;
                $aOthers['size'] += @filesize($sPath);
                if (filemtime($sPath) > $aOthers['last'])
                {
                    $aOthers['last'] = filemtime($sPath);
                }
            } else
            {
                if ($mData != "." && $mData != ".." && is_dir($sPath))
                {
                    //$aResult[$mData] = $this->getStats($mData);
                }
            }
        }
        $aResult['others'] = $aOthers;
        return $aResult;
    }

}
