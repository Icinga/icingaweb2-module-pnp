<?php

namespace Icinga\Module\Pnp\Authentication\IcingaWeb2;

use Exception;
use Predis\Client;

class RedisSessionInfoProvider implements SessionInfoProvider
{
    public function getInfoForSid($sid)
    {
        $parts = parse_url(session_save_path());
        if ($parts === false) {
            throw new Exception('Unable to parse Redis session save path');
        }
        if (! array_key_exists('scheme', $parts) || $parts['scheme'] !== 'tcp') {
            throw new Exception(
                'No scheme other than tcp:// is currently supported for Redis'
            );
        }

        if (! array_key_exists('host', $parts)) {
            throw new Exception('Redis host is required');
        }

        if (! array_key_exists('port', $parts)) {
            throw new Exception('Redis port is required');
        }

        $host = $parts['host'];
        $port = $parts['port'];

        parse_str($parts['query'], $query);
        if (array_key_exists('prefix', $query)) {
            $prefix = $query['prefix'];
        } else {
            $prefix = '';
        }
        if (array_key_exists('auth', $query)) {
            $auth = $query['auth'];
        } else {
            $auth = null;
        }
        $options = [
            'host' => $host,
            'port' => $port,
            'timeout' => 5,
        ];

        if ($auth !== null) {
            $options['password'] = $auth;
        }

        $redis = new Client($options);
        $key = $prefix . $sid;

        return $redis->get($key);
    }
}
