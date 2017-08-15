<?php

/** @var $this Icinga\Application\Modules\Module */
$this->provideConfigTab('config', array(
    'title' => $this->translate('Configure this module'),
    'label' => $this->translate('Config'),
    'url' => 'config'
));


$menuDisabled = $this->getConfig()->get('pnp4nagios', 'menu_disabled');
if (! $menuDisabled) {
    /** @var \Icinga\Web\Navigation\NavigationItem $section */
    $section = $this->menuSection('pnp');
    $section->setLabel('PNP')
        ->setUrl('pnp')
        ->setIcon('chart-line')
        ->setPriority(50);
}
