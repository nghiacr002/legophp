<?php

namespace APP\Application\Module\Core;

use APP\Engine\Module\Widget;

class DebugWidget extends Widget
{

    public function process()
    {
    	if(!$this->app->isDebug())
    	{
    		return false;
    	}
        $fTimeExecute = \APP\Engine\Debug::executeTime();
        $this->view->excuteTime = $fTimeExecute;
        $this->view->memoryUsage = memory_get_usage();
        $this->view->sRouter = $this->app->module->getCurrentPathRouter();
    }

}
