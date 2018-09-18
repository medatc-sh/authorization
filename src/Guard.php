<?php

namespace Authorization;

use Authorization\Redis\TryConnect;
use Authorization\Redis\UserCurOrganization;
use Authorization\Redis\UserForbidden;
use Authorization\Redis\UserModule;
use Authorization\Redis\UserOrganization;
use Authorization\Redis\UserPassport;
use Authorization\Redis\UserPermission;
use Authorization\Redis\UserProfile;
use Authorization\Redis\UserRefresh;
use Authorization\Redis\UserRole;

class Guard
{
    protected $platform;

    protected $organization;

    protected $uri;

    public function __construct($platform, $organization, $uri)
    {
        $this->platform = $platform;
        $this->organization = $organization;
        $this->uri = $uri;
    }

    public function auth($token)
    {
        $response = Gateway::request(
            UserProfile::Cmder,
            $this->platform,
            $this->organization,
            $this->uri,
            $token
        );
        return Gateway::response($response);
    }

    public function forbidden($token, $type)
    {
        $response = Gateway::request(
            UserForbidden::Cmder,
            $this->platform,
            $this->organization,
            $this->uri,
            $token,
            $type
        );
        return Gateway::response($response);
    }

    public function module($token, $type)
    {
        $response = Gateway::request(
            UserModule::Cmder,
            $this->platform,
            $this->organization,
            $this->uri,
            $token,
            $type
        );
        return Gateway::response($response);
    }

    public function organization($token)
    {
        $response = Gateway::request(
            UserOrganization::Cmder,
            $this->platform,
            $this->organization,
            $this->uri,
            $token
        );
        return Gateway::response($response);
    }

    public function curOrg($token)
    {
        $response = Gateway::request(
            UserCurOrganization::Cmder,
            $this->platform,
            $this->organization,
            $this->uri,
            $token
        );
        return Gateway::response($response);
    }


    public function passport($token)
    {
        $response = Gateway::request(
            UserPassport::Cmder,
            $this->platform,
            $this->organization,
            $this->uri,
            $token
        );
        return Gateway::response($response);
    }

    public function permission($token)
    {
        $response = Gateway::request(
            UserPermission::Cmder,
            $this->platform,
            $this->organization,
            $this->uri,
            $token
        );
        return Gateway::response($response);
    }

    public function role($token)
    {
        $response = Gateway::request(
            UserRole::Cmder,
            $this->platform,
            $this->organization,
            $this->uri,
            $token
        );
        return Gateway::response($response);
    }

    public function refresh($token)
    {
        $response = Gateway::request(
            UserRefresh::Cmder,
            $this->platform,
            $this->organization,
            $this->uri,
            $token
        );
        return Gateway::response($response);
    }

    public function connect()
    {
        Gateway::request(
            TryConnect::Cmder,
            $this->platform,
            $this->organization,
            $this->uri,
            md5(microtime()));
    }
}
