<?php

namespace Authorization;

use Predis\Client;

class Gateway
{
    protected static $client;

    protected static function init()
    {
        if (empty(static::$client)) {
            static::$client = new Client(['scheme' => 'tcp',
                'host' => env('AUTH_SERV_CLIENT', '127.0.0.1'),
                'port' => env('AUTH_SERV_CLIENT_PORT', 6379),
                'persistent' => true]);
        }
        return static::$client;
    }

    public static function request($class, $platform, $organization, $uri, $token, $type = '')
    {
        static::init()->getProfile()->defineCommand(class_basename($class), $class);
        $command = static::init()->createCommand(class_basename($class), [
            'parameter' => json_encode([
                'token' => $token,
                'platform' => $platform,
                'organization_id' => $organization,
                'uri' => $uri,
                'type' => $type,
            ])
        ]);
        /**
         * @var \Predis\Response\Status $response
         */
        $response = static::init()->executeCommand($command);
        return $response;
    }

    public static function response($response)
    {
        if (strpos($response->getPayload(), 'OK') !== false) {
            $data = @json_decode(ltrim($response->getPayload(), 'OK'), true);
        } else {
            $data = @json_decode(ltrim($response->getPayload(), 'ERR'), true);
        }
        return array_get($data, 'data');
    }
}