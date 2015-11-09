<?php
/* Icinga Web 2 | (c) 2013-2015 Icinga Development Team | GPLv2+ */

/** @var $this \Icinga\Application\Modules\Module */

$this->provideConfigTab('general', array(
    'title' => $this->translate('Adjust the general configuration of the Pnp4Nagios module'),
    'label' => $this->translate('General'),
    'url' => 'config'
));