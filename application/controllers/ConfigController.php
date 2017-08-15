<?php
/* Icinga Web 2 | (c) 2013-2015 Icinga Development Team | GPLv2+ */

namespace Icinga\Module\Pnp\Controllers;

use Icinga\Web\Controller;
use Icinga\Module\Pnp\Forms\Config\GeneralConfigForm;

class ConfigController extends Controller
{
    /**
     * General configuration
     */
    public function indexAction()
    {
        $this->assertPermission('config/modules');

        $form = new GeneralConfigForm();
        $form->setIniConfig($this->Config());
        $form->handleRequest();

        $this->view->form = $form;
        $this->view->tabs = $this->Module()->getConfigTabs()->activate('config');
    }
}
