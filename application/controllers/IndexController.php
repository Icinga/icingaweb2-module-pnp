<?php

use Icinga\Web\Controller\ActionController;

class Pnp4nagios_IndexController extends ActionController
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
