<?php

namespace APP\Engine;

class Utils
{

    public static function date_format($iUnixTime, $sFormatTime = null)
    {
        if (!$sFormatTime)
        {
            $sFormatTime = \APP\Engine\Application::getInstance()->getConfig('system', 'format_time');
        }
        return date($sFormatTime, $iUnixTime);
    }

    public static function file_size($iSize, $sReturnData = "kb")
    {
        if (!is_numeric($iSize))
        {
            $sSuffix = substr($iSize, -1);
            $iValue = substr($iSize, 0, -1);
            switch (strtoupper($sSuffix))
            {
                case 'P':
                    $iValue *= 1024;
                case 'T':
                    $iValue *= 1024;
                case 'G':
                    $iValue *= 1024;
                case 'M':
                    $iValue *= 1024;
                case 'K':
                    $iValue *= 1024;
                    break;
            }
            $iSize = $iValue;
        }
        $sFormatData = strtolower($sReturnData);
        $iConvert = 1;
        switch ($sFormatData)
        {
            case "kb":
                $iConvert = 1024;
                break;
            case "mb":
                $iConvert = 1024 * 1024;
                break;
            case "gb":
                $iConvert = 1024 * 1024 * 1024;
                break;
            case "tb":
                $iConvert = 1024 * 1024 * 1024 * 1024;
                break;
            case "pb":
                $iConvert = 1024 * 1024 * 1024 * 1024 * 1024;
                break;
            default:
                break;
        }
        $fConvert = $iSize / $iConvert;
        return round($fConvert, 3) . " " . ucfirst($sReturnData);
    }

    public static function isImage($sFile)
    {
        if (filesize($sFile) > 11)
        {
            return exif_imagetype($sFile);
        }
        return false;
    }

    public static function image_path($sPath, $sSize = "medium-square")
    {
        return (new Image())->getThumbUrl($sPath,$sSize);
    }
	public static function get_alpha_numberic_key($sKey)
	{
		$result = preg_replace("/[^a-zA-Z0-9]+/", "", $$sKey);
		return $result;
	}
}
