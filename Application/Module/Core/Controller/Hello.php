<?php

namespace APP\Application\Module\Core;

use APP\Engine\Module\Controller;
class HelloController extends Controller
{
    public function IndexAction()
    {
		system_display_result("Hello World!");
    }
}
