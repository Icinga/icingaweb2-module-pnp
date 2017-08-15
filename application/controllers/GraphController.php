<?php
/* Icinga Web 2 | (c) 2013-2017 Icinga Development Team | GPLv2+ */

namespace Icinga\Module\Pnp\Controllers;

use Icinga\Module\Pnp\Web\Controller;

class GraphController extends Controller
{
    public function indexAction()
    {
        $url = $this->getRequest()->getUrl();
        $queryString = $url->getQueryString();

        $this->view->url = sprintf(
            '%s/graph?%s',
            $this->getBaseUrl(),
            $queryString
        );

        $host = $this->getParam('host');
        $service = $this->getParam('srv');

        $serviceTitle = '';
        if ($service && $service !== '_HOST_') {
            $serviceTitle = sprintf(' | %s: %s', $this->translate('Service'), $service);
        }
        $this->view->title = $title = sprintf('%s: %s%s',
            $this->translate('Host'),
            $host,
            $serviceTitle
        );

        $this->getTabs()->add('graph', array(
            'label' => $title,
            'url'   => $url,
        ))->activate('graph');

        $this->setViewScript('index/iframe');
    }
}
