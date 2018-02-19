<?php

namespace Icinga\Module\Pnp\Authentication;

use Icinga\Module\Pnp\Authentication\IcingaWeb2\SessionInfo;

class AuthenticateWithIcingaWeb
{
    /** @var boolean */
    protected $isAuthenticated;

    protected $username;

    protected $cookieName;

    protected $sessionInfo;

    /** @var \Zend_Db_Adapter_Abstract */
    protected $db;

    public function __construct()
    {
        $this->sessionInfo = new SessionInfo();
    }

    public function isAuthenticated()
    {
        return $this->sessionInfo->isAuthenticated();
    }

    public function getUsername()
    {
        return $this->sessionInfo->getUsername();
    }

    public function isAuthorizedFor($host, $service)
    {
        // TODO: Finish and test filters
        // $provicer = new Ido

        return true;
    }
}
