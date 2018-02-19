<?php

namespace Icinga\Module\Pnp\Authentication\IcingaWeb2;

use Icinga\Data\Filter\Filter;
use Icinga\Module\Monitoring\Backend\MonitoringBackend;

class IdoAuthorizationProvider extends AuthorizationProvider
{
    public function isAuthorizedFor($host, $service)
    {
        $restrictions = $this->user->getRestrictions('monitoring/filter/objects');
        if (empty($restrictions)) {
            return true;
        } else {
            $filters = [];
            foreach ($restrictions as $restriction) {
                if ($restriction === '*') {
                    return true;
                } else {
                    $filters[] = Filter::fromQueryString($restriction);
                }
            }

            $filter = Filter::matchAny($filters);
            $filter->setAllowedFilterColumns([
                'host_name',
                'hostgroup_name',
                'instance_name',
                'service_description',
                'servicegroup_name',
                function ($c) {
                    return preg_match('/^_(?:host|service)_/i', $c);
                }
            ]);
        }

        if ($service === null) {
            $query = $this->prepareHostQuery($host);
        } else {
            $query = $this->prepareServiceQuery($host, $service);
        }

        // TODO.
        FilterRenderer::applyToQuery($filter, $query);

        return false;
    }

    protected function prepareHostQuery($host)
    {
        return $this->db()->select()->from(
            ['o' => 'icinga_objects'],
            ['host' => 'o.name1']
        )->where('o.is_active = 1')
            ->where('o.objecttype_id = 1')
            ->where('o.name1 = ?', $host);
    }

    protected function prepareServiceQuery($host, $service)
    {
        return $this->db()->select()->from(
            ['o' => 'icinga_objects'],
            ['host' => 'o.name1']
        )->where('o.is_active = 1')
            ->where('o.objecttype_id = 2')
            ->where('o.name1 = ?', $host)
            ->where('o.name2 = ?', $service);
    }

    protected function db()
    {
        if ($this->db === null) {
            $this->db = MonitoringBackend::instance()
                ->getResource()
                ->getDbAdapter();
        }

        return $this->db;
    }

}
