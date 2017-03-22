<?php

namespace APP\Library;
class Sitemap
{
	const EXT = '.xml';
	const SCHEMA = 'http://www.sitemaps.org/schemas/sitemap/0.9';
	const DEFAULT_PRIORITY = 0.5;
	private $_sFileName = "";
	private $_aXMLs = array();
	private $_bIsStart = false;
	private $_sBasePath = "";
	private $_sType = "item";
	public function __construct()
	{
		$this->_sBasePath = APP_PUBLIC_PATH. 'Sitemap'. APP_DS;
	}
	public function setBasePath($sPath)
	{
		$this->_sBasePath = $sPath;
		return $this;
	}
	/**
	 *
	 * @param unknown $sFileName
	 * @param string $sSitemapType: should be sitemapindex or urlset
	 * @return \APP\Library\Sitemap
	 */
	public function start($sFileName, $sSitemapType = "urlset")
	{
		$this->_sFileName = $sFileName;
		$this->_bIsStart = true;
		$this->_sType = $sSitemapType;
		$this->_aXMLs = array();
		$this->_aXMLs[] = '<?xml version="1.0" encoding="utf-8"?>';
		$this->_aXMLs[] = '<'.$sSitemapType.' xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
		return $this;
	}
	public function addItem($aItem = array())
	{
		$aFormatedItem = $this->_buildItem($aItem);
		switch ($this->_sType)
		{
			case "sitemapindex":
				$this->_aXMLs[] = '<sitemap>';
				$this->_aXMLs = array_merge($this->_aXMLs,$aFormatedItem);
				$this->_aXMLs[] = '</sitemap>';
				break;
			case "urlset":
				$this->_aXMLs[] = '<url>';
				$this->_aXMLs = array_merge($this->_aXMLs,$aFormatedItem);
				$this->_aXMLs[] = '</url>';
				break;
		}

		return $this;
	}
	private function _buildItem($aItem)
	{
		$aXML = array();
		foreach($aItem as $sKey => $mValue)
		{
			if(is_array($mValue))
			{
				if(count($mValue))
				{
					$aXMLChilds = $this->_buildItem($mValue);
					$aXML[] = '<'.$sKey.'>' . implode("" ,$aXMLChilds).'</'.$sKey.'>';
				}

			}
			else
			{
				$aXML[] = '<'.$sKey.'>' . $mValue.'</'.$sKey.'>';
			}
		}
		return $aXML;
	}
	public function end()
	{
		$this->_aXMLs[] = '</'. $this->_sType.'>';
		return $this;
	}
	public function write()
	{
		if(!$this->_sFileName)
		{
			return false;
		}
		$sFilePath = $this->_sBasePath . $this->_sFileName;
		@file_put_contents($sFilePath, implode('',$this->_aXMLs));
		return $this;
	}
	public function clean()
	{
		$this->_aXMLs = array();
		$this->_bIsStart = false;
		$this->_sFileName = "";
		$this->_sType = "sitemapindex";
		return $this;
	}
}