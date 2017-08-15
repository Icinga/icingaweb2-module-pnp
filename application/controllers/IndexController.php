<?php
/* Icinga Web 2 | (c) 2013-2017 Icinga Development Team | GPLv2+ */

namespace Icinga\Module\Pnp\Controllers;

use Icinga\Module\Pnp\Web\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        $this->redirectNow($this->getRequest()->getUrl()->setPath('pnp/graph'));
    }
}
