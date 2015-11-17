<?php
/* Icinga Web 2 | (c) 2013-2015 Icinga Development Team | GPLv2+ */

namespace Icinga\Module\Pnp\Forms\Config;

use Icinga\Forms\ConfigForm;

class GeneralConfigForm extends ConfigForm
{
    /**
     * Initialize this form
     */
    public function init()
    {
        $this->setName('form_config_pnp4nagios_general');
        $this->setSubmitLabel(t('Save Changes'));
    }

    /**
     * {@inheritdoc}
     */
    public function createElements(array $formData)
    {
        $this->addElement(
            'text',
            'pnp4nagios_config_dir',
            array(
                'value'         => '/etc/pnp4nagios',
                'label'         => $this->translate('Pnp4Nagios Configuration'),
                'description'   => $this->translate('The path to the configuration of your Pnp4Nagios installation.')
            )
        );
        $this->addElement(
            'text',
            'pnp4nagios_base_url',
            array(
                'value'         => '/pnp4nagios',
                'label'         => $this->translate('Pnp4Nagios Url'),
                'description'   => $this->translate('The base URL of your Pnp4Nagios installation.')
            )
        );
    }
}
