<?php
/* Icinga Web 2 | (c) 2013-2017 Icinga Development Team | GPLv2+ */

namespace Icinga\Module\Pnp\Controllers;

use Icinga\Web\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        $baseUrl = rtrim($this->Config()->get('pnp4nagios', 'base_url', '/pnp4nagios'), '/');

        $this->view->url = sprintf(
             '%s/graph?host=%s&srv=%s&view=%d',
             $baseUrl,
             urlencode($this->getParam('host')),
             urlencode($this->getParam('srv')),
             $this->getParam('view')
        );
    }
}
