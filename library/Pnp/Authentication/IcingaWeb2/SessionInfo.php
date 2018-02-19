<?php

namespace Icinga\Module\Pnp\Authentication\IcingaWeb2;

use Exception;
use Icinga\Exception\AuthenticationException;
use Icinga\User;

class SessionInfo
{
    /** @var boolean */
    protected $isAuthenticated;

    /** @var User */
    protected $user;

    protected $username;

    protected $cookieName;

    protected $sessionInfo;

    /** @var \Zend_Db_Adapter_Abstract */
    protected $db;

    /**
     * @return boolean
     */
    public function isAuthenticated()
    {
        if ($this->isAuthenticated === null) {
            $this->checkSession();
        }

        return $this->isAuthenticated;
    }

    /**
     * @return User
     * @throws Exception
     */
    public function getUser()
    {
        if (! $this->isAuthenticated()) {
            throw new AuthenticationException('Not authenticated, no user available');
        }

        return $this->user;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->getUser()->getUsername();
    }

    protected function getSessionInfo()
    {
        if ($this->sessionInfo === null) {
            $this->sessionInfo = $this->retrieveSessionInfo();
        }

        return $this->sessionInfo;
    }

    protected function checkSession()
    {
        $info = $this->getSessionInfo();
        if (is_array($info) && array_key_exists('user', $info)) {
            /** @var \Icinga\User $user */
            $user = $info['user'];

            $this->isAuthenticated = true;
            $this->user = $user;
        } else {
            $this->isAuthenticated = false;
            $this->user = false;
        }
    }

    protected function getSessionCookieName()
    {
        return 'Icingaweb2';
    }

    /**
     * Currently unused, decide how to configure in SSO environments
     */
    protected function redirectToLogin()
    {
        $url = $this->getIcingaWebBaseUrl();
        header("Location: $url");
    }

    protected function getIcingaWebBaseUrl()
    {
        return '/icingaweb2/';
    }

    protected function getSid()
    {
        $name = $this->getSessionCookieName();
        if (! array_key_exists($name, $_COOKIE)) {
            return false;
        }

        $sid = $_COOKIE[$name];

        if (preg_match('/^[A-Za-z0-9]{16,32}$/', $sid)) {
            return $sid;
        } else {
            return false;
        }
    }

    /**
     * @return array|false
     * @throws Exception
     */
    protected function retrieveSessionInfo()
    {
        $sid = $this->getSid();

        switch (session_module_name()) {
            case 'files':
                $provider = new PhpFilesSessionInfoProvider();
                break;
            case 'redis':
                $provider = new RedisSessionInfoProvider();
                break;
            default:
                throw new Exception('I only support "files" or "redis" Session storage');
        }

        $raw = $provider->getInfoForSid($sid);
        $info = $this->unserializeSessionData($raw);
        if ($info === false) {
            throw new Exception(
                'Unable to decode session data'
            );
        }

        return $info;
    }

    protected function unserializePhp($raw)
    {
        $result = [];
        $offset = 0;
        $length = strlen($raw);
        while ($offset < $length) {
            if (false === strstr(substr($raw, $offset), '|')) {
                $remainingBytes = strlen(substr($raw, $offset));
                throw new Exception("Invalid data, $remainingBytes remaining");
            }
            $pos = strpos($raw, '|', $offset);
            $num = $pos - $offset;
            $key = substr($raw, $offset, $num);
            $offset += $num + 1;
            $data = substr($raw, $offset);
            $result[$key] = $this->saferUnserialize($data);
            $offset += strlen($data);
        }

        return $result;
    }

    protected function unserializePhpBinary($raw)
    {
        $result = [];
        $offset = 0;
        $length = strlen($raw);
        while ($offset < $length) {
            $num = ord($raw[$offset]);
            $offset += 1;
            $key = substr($raw, $offset, $num);
            $offset += $num;
            $data = substr($raw, $offset);
            $result[$key] = $this->saferUnserialize($data);
            $offset += strlen($data);
        }

        return $result;
    }

    protected function saferUnserialize($raw)
    {
        if (PHP_VERSION_ID < 70000) {
            return @unserialize($raw);
        } else {
            $expectedClasses = [
                'Icinga\\User',
                'Icinga\\Authentication\\Role',
                'Icinga\\User\\Preferences',
            ];

            return @unserialize($raw, ['allowed_classes' => $expectedClasses]);
        }
    }


    protected function unserializeSessionData($raw)
    {
        $handler = ini_get('session.serialize_handler');
        switch ($handler) {
            case 'php':
                return $this->unserializePhp($raw);

            case 'php_binary':
                return $this->unserializePhpBinary($raw);

            case 'php_serialize':
                return $this->saferUnserialize($raw);

            default:
                throw new Exception(
                    'Unsupported session.serialize_handler: ' . $handler
                );
        }
    }
}
