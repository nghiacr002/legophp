<?php

namespace APP\Library\Cache;

interface Storage
{

    public function set($sCacheName, $mData, $iTimeToLive = 0, $sFolder = "");

    public function get($sCacheName,$sFolder = "");

    public function remove($sCacheName);

    public function clean($sType = "");

    public function getCaches();

    public function getFolder($sFolder);

	public function flush();
}
