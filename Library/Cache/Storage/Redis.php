<?php
namespace APP\Library\Cache\Storage;

use APP\Engine\Object;
require_once APP_PATH_LIB . 'vendor'. APP_DS . 'predis/predis/autoload.php';
class Redis extends Object implements \APP\Library\Cache\Storage
{
	protected $_oClient;
	public function __construct($aOptions = null)
	{
		$this->_oClient = new \Predis\Client($aOptions);
	}
	public function getFolder($sFolder = "")
	{
		if(empty($sFolder))
		{
			$sFolder = "System";
		}
		return ucfirst($sFolder);
	}
	public function set($sCacheName, $mData, $iTimeToLive = 0, $sFolder = "")
	{
		if(!$mData){
			return null;
		}
		$sFolder = $this->getFolder($sFolder);
		$this->_oClient->expire($sFolder.'/'.$sCacheName, $iTimeToLive);
		$sData = var_export($mData, true);
		$sContent = " \$aCacheData = " . $sData . ";";
		return $this->_oClient->set($sFolder.'/'.$sCacheName,$sContent);
	}

	public function get($sCacheName, $sFolder = "")
	{
		if(defined('APP_NO_CACHE') && APP_NO_CACHE)
		{
			return null;
		}
		$sFolder = $this->getFolder($sFolder);
		$mData =  $this->_oClient->get($sFolder.'/'.$sCacheName);
		if($mData)
		{
			@eval($mData);
			if(isset($aCacheData))
			{
				return $aCacheData;
			}
		}
		return null;
	}
	public function remove($sCacheName, $sFolder = "")
	{
		$sFolder = $this->getFolder($sFolder);
		return $this->_oClient->del(array($sFolder.'/'.$sCacheName));
	}
	public function flush()
	{
		return $this->_oClient->flushDb();
	}
	public function clean($sType = "", $sPrefix = "")
	{
		list($iCursor, $aKeys) = $this->_oClient->scan(0,array('match' => $sType.'/'.$sPrefix.'*'));
		if(count($aKeys))
		{
			$this->_oClient->del($aKeys);
		}
	}
	public function getCaches()
	{
		return $this->_oClient->keys('*');
	}
}