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
        $this->setSubmitLabel($this->translate('Save Changes'));
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
                'label'         => $this->translate('PNP4Nagios configuration'),
                'description'   => $this->translate('PNP4Nagios configuration path name (e.g. /etc/pnp4nagios)')
            )
        );
        $this->addElement(
            'text',
            'pnp4nagios_base_url',
            array(
                'value'         => '/pnp4nagios',
                'label'         => $this->translate('PNP4Nagios url'),
                'description'   => $this->translate('The base URL of your PNP4Nagios installation (e.g. /pnp4nagios)')
            )
        );
    }
}
