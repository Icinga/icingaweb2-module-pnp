<?php

use Icinga\Web\Controller\ModuleActionController;

class Pnp4nagios_ConfigController extends ModuleActionController
{
    public function indexAction()
    {
        $this->view->tabs = $this->Module()->getConfigTabs()->activate('config');
        $this->view->hint = sprintf($this->translate(
            'Configuration form is still missing in this prototype.'
          . ' In case your PNP4Nagios config path is not %s or your base'
          . ' PNP4Nagios web url differs from %s please create a config file'
          . ' in %s following this example:'),
            '/etc/pnp4nagios',
            '/pnp4nagios',
            $this->Config()->getConfigFile()
        );
    }
}
