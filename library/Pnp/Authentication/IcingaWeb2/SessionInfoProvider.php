<?php

namespace Icinga\Module\Pnp\Authentication\IcingaWeb2;

interface SessionInfoProvider
{
    /**
     * @param string $sid
     * @return array mixed
     */
    public function getInfoForSid($sid);
}
