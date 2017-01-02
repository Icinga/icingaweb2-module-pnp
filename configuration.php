<?php

/** @var $this Icinga\Application\Modules\Module */
$this->provideConfigTab('config', array(
    'title' => $this->translate('Configure this module'),
    'label' => $this->translate('Config'),
    'url' => 'config'
));
