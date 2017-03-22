<?php
namespace APP\Engine;
class Event extends Object
{
	protected $_aChannels = array();
	public function subscribe($sEventName, $mCallableFunction = null , $sChannelName = "system")
	{
		if(!isset($this->_aChannels[$sChannelName]))
		{
			$this->_aChannels[$sChannelName] = array();
		}
		if(!isset($this->_aChannels[$sChannelName][$sEventName]))
		{
			$this->_aChannels[$sChannelName][$sEventName] = array();
		}
		$sHash = $this->getHash($mCallableFunction);
		$this->_aChannels[$sChannelName][$sEventName][$sHash] = $mCallableFunction;
	}
	public function unsubscribe($sHash, $sChannelName = "system")
	{
		if(!empty($sChannelName))
		{
			$aChannel = isset($this->_aChannels[$sChannelName]) ? $this->_aChannels[$sChannelName] : array();
			$aChannels = array(
				$sChannelName => $aChannel
			);
		}
		else
		{
			$aChannels = $this->_aChannels;
		}
		if(count($aChannels))
		{
			foreach($aChannels as $sChannelNameKey => $aChannel)
			{
				if(is_array($aChannel) && count($aChannel))
				{
					foreach($aChannel as $sEventName => $aEvents)
					{
						if(is_array($aEvents) && count($aEvents))
						{
							foreach($aEvents as $sHashKey => $mFunction)
							{
								if($sHash == $sHashKey)
								{
									unset($this->_aChannels[$sChannelNameKey][$sEventName][$sHash]);
									return true;
								}
							}
						}
					}
				}
			}
		}
		return false;
	}
	public function publish($sEventName, $aParams = array(), $sChannelName = "system")
	{
		if(!empty($sChannelName))
		{
			$aChannel = isset($this->_aChannels[$sChannelName]) ? $this->_aChannels[$sChannelName] : array();
			$aChannels = array(
				$sChannelName => $aChannel
			);
		}
		else
		{
			$aChannels = $this->_aChannels;
		}

		foreach($aChannels as $sChannelNameKey => $aChannel)
		{
			if(is_array($aChannel) && count($aChannel))
			{
				foreach($aChannel as $sEventName => $aEvents)
				{
					if(is_array($aEvents) &&  count($aEvents))
					{
						foreach($aEvents as $iKey => $mCallableFunction)
						{
							if(is_callable($mCallableFunction))
							{
								call_user_func($mCallableFunction, $aParams);
							}
						}
					}
				}
			}
		}

	}
	public function getSubcribers($sChannel = "system")
	{
		if(!empty($sChannel))
		{
			return isset($this->_aChannels[$sChannel]) ? $this->_aChannels[$sChannel] : array();
		}
		return $this->_aChannels;
	}

	protected function getHash($mCallableFunction)
	{
		$sHash = "";

		if(is_array($mCallableFunction))
		{
			if(is_object($mCallableFunction[0]))
			{
				$sClass = get_class($mCallableFunction[0]);
			}
			else
			{
				$sClass = $mCallableFunction[0];
			}
			$mCallableFunction[0] = $sClass;
			$mCallableFunction = serialize($mCallableFunction);
		}
		$sHash = md5($mCallableFunction);
		return $sHash;
	}
}