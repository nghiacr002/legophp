<?php

namespace APP\Engine;

class Logger
{

    public static function write($mContent, $sMode = "a+", $sFileName = null)
    {
        if (!$sFileName)
        {
            $sFileName = "main.log";
        }
        $sFileName = APP_PUBLIC_PATH . "Log" . APP_DS . $sFileName;
        $sLogContent = "";
        if (is_array($mContent))
        {
            $sLogContent = implode(PHP_EOL, $mContent);
        } elseif (is_object($mContent))
        {
            $sLogContent = var_export($mContent, true);
        } else
        {
            $sLogContent = $mContent;
        }
        $fp = @fopen($sFileName, $sMode);
        if (!$fp)
        {
            return false;
        }
        @fwrite($fp, $sLogContent);
        fclose($fp);
        return true;
    }

    public static function error(\Exception $ex, $sSystemCode = null)
    {
        if (!$sSystemCode)
        {
            if (($ex instanceof AppException))
            {
                $sSystemCode = $ex->hashCode();
            } else
            {
                $sSystemCode = (new AppException())->hashCode();
            }
        }
        $mLog = array(
            PHP_EOL,
            "Error Code: " . $sSystemCode . " at " . date("l jS \of F Y h:i:s A"),
            "[" . $ex->getCode() . "] " . $ex->getMessage(),
            $ex->getTraceAsString()
        );

        return Logger::write($mLog);
    }

}
