<?php
/* Icinga Web 2 | (c) 2013-2015 Icinga Development Team | GPLv2+ */

namespace Icinga\Module\Pnp4nagios\Forms\Config;

use Icinga\Forms\ConfigForm;
use Icinga\Web\Notification;

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

    /**
     * {@inheritdoc}
     */
    public function onSuccess()
    {
        $sections = array();
        foreach ($this->getValues() as $sectionAndPropertyName => $value) {
            if ($value !== '') {
                list($section, $property) = explode('_', $sectionAndPropertyName, 2);
                $sections[$section][$property] = $value;
            }
        }

        foreach ($sections as $section => $config) {
            $this->config->setSection($section, $config);
        }

        if ($this->save()) {
            Notification::success(t('New configuration has successfully been stored'));
        } else {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function onRequest()
    {
        $values = array();
        foreach ($this->config as $section => $properties) {
            foreach ($properties as $name => $value) {
                $values[$section . '_' . $name] = $value;
            }
        }

        $this->populate($values);
    }
}
