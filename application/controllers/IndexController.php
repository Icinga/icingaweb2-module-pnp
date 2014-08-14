<?php

use Icinga\Web\Controller\ActionController;

class Pnp4nagios_IndexController extends ActionController
{
    public function indexAction()
    {
        $baseUrl = '/pnp4nagios';
        $config = $this->Config()->get('pnp4nagios');

        if ($config) {
            $baseUrl = rtrim($config->get('base_url', $baseUrl), '/');
        }

        $this->view->url = sprintf(
             '%s/graph?host=%s&srv=%s&view=%d',
             $baseUrl,
             urlencode($this->getParam('host')),
             urlencode($this->getParam('srv')),
             $this->getParam('view')
        );
    }
}
