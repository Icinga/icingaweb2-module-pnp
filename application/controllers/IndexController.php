<?php
/* Icinga Web 2 | (c) 2013-2017 Icinga Development Team | GPLv2+ */

namespace Icinga\Module\Pnp\Controllers;

use Icinga\Module\Pnp\Web\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        $this->getTabs()->activate('pnp');

        $defaultQuery = $this->Config()->get('pnp4nagios', 'default_query', 'host=.pnp-internal&srv=runtime');

        $this->view->title = 'PNP';
        $this->view->url = sprintf(
            '%s/graph?%s',
            $this->getBaseUrl(),
            $defaultQuery
        );

        $this->setViewScript('index/iframe');
    }
}
