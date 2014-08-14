<?php

use Icinga\Web\Controller\ModuleActionController;

class Pnp4nagios_ConfigController extends ModuleActionController
{
    public function indexAction()
    {
        $this->view->tabs = $this->Module()->getConfigTabs()->activate('config');
        $hintHtml = $this->view->escape($this->translate(
            'Configuration form is still missing in this prototype.'
          . ' In case your PNP4Nagios config path is not %s or your base'
          . ' PNP4Nagios web url differs from %s please create a config file'
          . ' in %s following this example:'
        ));
        $this->view->escapedHint = sprintf(
            $hintHtml,
            '<b>/etc/pnp4nagios</b>',
            '<b>/pnp4nagios</b>',
            '<b>' . $this->Config()->getConfigFile() . '</b>'
        );
    }
}
