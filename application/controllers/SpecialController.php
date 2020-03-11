<?php

namespace Icinga\Module\Pnp\Controllers;

use Icinga\Module\Pnp\Web\Controller;

class SpecialController extends Controller
{
    public function indexAction()
    {
        $url = $this->getRequest()->getUrl();
        $queryString = $url->getQueryString();

        $this->view->url = \sprintf(
            '%s/special?%s',
            $this->getBaseUrl(),
            $queryString
        );

        $this->getTabs()->add('special', [
            'label' => $this->translate('Special'),
            'url'   => $url,
        ])->activate('special');

        $this->setViewScript('index/iframe');
    }
}
