<?php

namespace Icinga\Module\Pnp\Web;

use Icinga\Web\Controller as IcingaController;

class Controller extends IcingaController
{
    public function init()
    {
        $this->getTabs()->add('pnp', array(
            'label' => $this->translate('PNP'),
            'url'   => 'pnp',
        ));
    }

    protected function setViewScript($name)
    {
        $this->_helper->viewRenderer->setNoController(true);
        $this->_helper->viewRenderer->setScriptAction($name);
    }

    protected function getBaseUrl()
    {
        return rtrim($this->Config()->get('pnp4nagios', 'base_url', '/pnp4nagios'), '/');
    }
}
