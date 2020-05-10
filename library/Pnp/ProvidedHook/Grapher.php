<?php

namespace Icinga\Module\Pnp\ProvidedHook;

use Icinga\Application\Config;
use Icinga\Exception\ConfigurationError;
use Icinga\Application\Hook\GrapherHook;
use Icinga\Module\Monitoring\Object\MonitoredObject;
use Icinga\Module\Monitoring\Object\Host;
use Icinga\Module\Monitoring\Object\Service;
use Icinga\Web\Url;

class Grapher extends GrapherHook
{
    protected $hasPreviews = true;

    protected $pnpConfig;

    protected $pnpViews;

    protected $configDir = '/etc/pnp4nagios';

    protected $baseUrl = '/pnp4nagios';

    protected function init()
    {
        $cfg = Config::module('pnp')->getSection('pnp4nagios');
        $this->configDir = rtrim($cfg->get('config_dir', $this->configDir), '/');
        $this->baseUrl   = rtrim($cfg->get('base_url', $this->baseUrl), '/');
        $this->readPnpConfig();
    }

    public function has(MonitoredObject $object)
    {
        if ($object instanceof Host) {
            $service = '_HOST_';
        } elseif ($object instanceof Service) {
            $service = $object->service_description;
        } else {
            return false;
        }

        $host = $object->host_name;
        return is_file($this->getXmlFilename($host, $service));
    }

    public function getPreviewHtml(MonitoredObject $object)
    {
        if (! $object->process_perfdata) {
            return '';
        }

        // Skip preview images when missing, for local installations only
        if (false === strpos($this->baseUrl, '://') && ! $this->has($object)) {
            return '';
        }

        if ($object instanceof Host) {
            $service = '_HOST_';
        } elseif ($object instanceof Service) {
            $service = $object->service_description;
        } else {
            return '';
        }

        $host = $object->host_name;

        $html = '<table style="width: 100%; max-width: 40em; text-align: center;'
              . ' font-size: 0.8em; line-height: 0.8em; table-layout: fixed">'
              . "\n  <tr>\n";
        $viewKeys = array_reverse(array_keys($this->pnpViews));
        foreach ($viewKeys as $view) {
            $html .= '<th>' . htmlspecialchars($this->getViewName($view)) . "</th>\n";
        }
        $html .= "  </tr>\n  <tr>\n";
        foreach ($viewKeys as $view) {
            $html .= '    <td style="border-left: 1px solid #555; padding-right: 3px">'
                   . $this->getPreviewImg($host, $service, $view)
                   . "</td>\n";
        }
        $html .= "</tr></table>\n";
        return $html;
    }

    // Currently unused, but would work fine. This is for tiny preview images
    // in list views
    public function getSmallPreviewImage($host, $service = null)
    {
        if ($service === null) {
            $service = '_HOST_';
        }

        return sprintf(
            '<img src="%s/image?host=%s&srv=%s&view=0&source=0&h=20&w=50" alt="" style="float: right" />',
            $this->baseUrl,
            urlencode($this->pnpClean($host)),
            urlencode($this->pnpClean($service))
        );
    }

    private function listAdditionalConfigFiles()
    {
        $files = array();
        $base = $this->configDir . '/config';

        $file = $base . '_local.php';
        if (file_exists($file) && is_readable($file)) {
            $files[] = $file;
        }

        $confd = $base . '.d';
        if (is_dir($confd) && is_readable($confd)) {
            $dh = opendir($confd);
            while ($file === readdir($dh)) {
                if ($file[0] === '.') continue;
                if (substr($file, -4) !== '.php') continue;

                $filename = $confd . '/' . $file;
                if (is_file($filename) && is_readable($filename)) {
                    $files[] = $filename;
                }
            }

            closedir($dh);
        }

        return $files;
    }

    // This reads the PNP4Nagios config and makes it's $conf available
    private function readPnpConfig()
    {
        $file = $this->configDir . '/config.php';

        if (! is_readable($file)) {
            throw new ConfigurationError(
                sprintf(
                    'Cannot read PNP4Nagios-Web config file "%s"',
                    $file
                )
            );
        }
        if (! include($file)) {
            throw new ConfigurationError(
                sprintf(
                    'Including PNP4Nagios-Web config "%s" failed',
                    $file
                )
            );
        }

        if (! isset($views)) {
            $views = array();
        }

        foreach ($this->listAdditionalConfigFiles() as $file) {
            $oldViews = $views;
            include $file;
            if (empty($views)) {
                $views = $oldViews;
            }
        } 

        if (! isset($conf) || ! is_array($conf)) {
            throw new ConfigurationError(
                sprintf(
                    'There is no $conf in your PNP4Nagios config file "%s"',
                    $file
                )
            );
        }

        if (! isset($views) || ! is_array($views)) {
            throw new ConfigurationError(
                sprintf(
                    'There is no $views array in your PNP4Nagios config file "%s"',
                    $file
                )
            );
        }

        if (! array_key_exists('rrdbase', $conf)) {
            throw new ConfigurationError(
                sprintf(
                    'There is no rrdbase in your PNP4Nagios config file "%s"',
                    $file
                )
            );
        }
        $this->pnpConfig = $conf;
        $this->pnpViews  = $views;
        return $this;
    }

    // pnp_Core::clean
    private function pnpClean($string)
    {
        if ($string === false) {
            return null;
        }
        return preg_replace('~[ :/\\\]~', '_', $string);
    }

    private function getBasePath($host, $service)
    {
        if ($service === null) {
            $service = '_HOST_';
        }
        return rtrim($this->pnpConfig['rrdbase'], '/')
              . '/' . $this->pnpClean($host) . '/'
              . $this->pnpClean($service);
    }

    private function getRrdFilename($host, $service)
    {
        return $this->getBasePath($host, $service) . '.rrd';
    }

    private function getXmlFilename($host, $service)
    {
        return $this->getBasePath($host, $service) . '.xml';
    }

    private function getPreviewImg($host, $service, $view)
    {
        $viewName = $this->getViewName($view);

        $host = $this->pnpClean($host);
        $service = $this->pnpClean($service);

        $title = $service === '_HOST_' ? sprintf(
            '%s, %s', $host, $viewName
        ) : sprintf(
            '%s on %s, %s', $service, $host, $viewName
        );

        $url = Url::fromPath('pnp4nagios/graph', array(
            'host' => $this->pnpClean($host),
            'srv' => $this->pnpClean($service),
            'view' => $view
        ));
        $imgUrl = sprintf(
            '%s/image?host=%s&srv=%s&view=%d&source=0&w=120&h=30',
            $this->baseUrl,
            urlencode($this->pnpClean($host)),
            urlencode($this->pnpClean($service)),
            $view
        );

        $html = '<a href="%s" title="%s"><img src="%s" alt="%s" width="100%%" height="30" /></a>';

        return sprintf(
            $html,
            $url,
            htmlspecialchars($title),
            $imgUrl,
            htmlspecialchars(mt('pnp', 'Loading') . '...')
        );
    }

    protected function getViewName($view)
    {
        return mt('pnp4nagios', $this->pnpViews[$view]['title']);
    }

    private function unusedFunctionAllowingToTranslateForeignStrings()
    {
        mt('pnp4nagios', '4 Hours');
        mt('pnp4nagios', '25 Hours');
        mt('pnp4nagios', 'One Week');
        mt('pnp4nagios', 'One Month');
        mt('pnp4nagios', 'One Year');
    }
}
