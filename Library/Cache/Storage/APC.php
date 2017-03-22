<?php

namespace APP\Library\Cache\Storage;

class APC implements \APP\Library\Storage
{

    public function set($sCacheName, $mData, $iTimeToLive = 0)
    {
        apc_add($sCacheName, $mData, $iTimeToLive);
        return $this;
    }

    public function get($sCacheName)
    {
        return apc_fetch($sCacheName);
    }

    public function remove($sCacheName)
    {
        apc_delete($sCahename);
    }

    public function clean($sType = "")
    {
        apc_clear_cache($sType);
    }
	public function getCaches()
	{
		return null;
	}
}
