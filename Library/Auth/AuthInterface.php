<?php

namespace APP\Library\Auth;

interface AuthInterface
{

    public function __construct($aParams = null);

    public function isAuthenticated();

    public function setParams($aParams = null);

    public function unauthorized();

    public function getViewer();

    public function getAcl();
}
