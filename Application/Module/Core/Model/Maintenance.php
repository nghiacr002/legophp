<?php

namespace APP\Application\Module\Core\Model;

class Maintenance
{

    public function removeItem($sPath, $bRemoveRoot = false)
    {
        if (is_file($sPath))
        {
            @unlink($sPath);
        } elseif (is_dir($sPath))
        {
            $oHandle = @opendir($sPath);
            while (false !== ($sFile = readdir($oHandle)))
            {
                if ($sFile != "." && $sFile != "..")
                {
                    $this->removeItem($sPath . APP_DS . $sFile, true);
                }
            }
            @closedir($oHandle);
            if ($bRemoveRoot)
            {
                @rmdir($sPath);
            }
        }
    }

    public function getFolderInfo($sPath)
    {
        $iFileSize = 0;
        $iTotalFile = 0;
        if (is_file($sPath))
        {
            $iFileSize = $iFileSize + filesize($sPath);
            $iTotalFile++;
        } elseif (is_dir($sPath))
        {
            $oHandle = @opendir($sPath);
            while (false !== ($sFile = readdir($oHandle)))
            {
                if ($sFile != "." && $sFile != "..")
                {
                    list($iTotalFileTmp, $iFileSizeTmp) = $this->getFolderInfo($sPath . APP_DS . $sFile);
                    $iTotalFile = $iTotalFile + $iTotalFileTmp;
                    $iFileSize = $iFileSize + $iFileSizeTmp;
                }
            }
            @closedir($oHandle);
        }
        return array($iTotalFile, $iFileSize);
    }

    public function getFolders($sPathFolder = null)
    {
        if ($sPathFolder == null)
        {
            $sPathFolder = APP_PUBLIC_PATH;
        }
        $aResults = array();
        $aFolders = @scandir($sPathFolder);
        if ($aFolders && count($aFolders))
        {
            foreach ($aFolders as $iKey => $mData)
            {
                $sPath = $sPathFolder . $mData;
                if ($mData != "." && $mData != ".." && is_dir($sPath))
                {
                    $aResults[$mData] = array(
                        'path' => $sPath,
                        'last_modified' => @filemtime($sPath . APP_DS . '.')
                    );
                }
            }
        }
        return $aResults;
    }

}
