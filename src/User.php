<?php

namespace Authorization;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

final class User implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;
    public $id;
    public $name;
    public $token;
    protected static $instance;
    protected $forbidden;

    private function __construct()
    {
    }

    protected function fill($attributes)
    {
        foreach ($attributes as $key => $value) {
            $this->{$key} = $value;
        }
        return $this;
    }

    /**
     * @param $attributes
     * @return User
     */
    public static function create($attributes)
    {
        if (empty($attributes)) {
            return null;
        }
        if (empty(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance->fill($attributes);
    }

    public function id()
    {
        return $this->user_id;
    }

    public function name()
    {
        return $this->name;
    }

    public function token()
    {
        return $this->token;
    }

    public function getAuthIdentifier()
    {
        return $this->user_id;
    }

    public function forbidden()
    {
        return $this->forbidden;
    }

    public function organizations()
    {
        return app('authorization')->organization($this->token);
    }

    public function modules($type)
    {
        return app('authorization')->module($this->token, $type);
    }

    public function permissions()
    {
        return app('authorization')->permission($this->token);
    }

    public function roles()
    {
        return app('authorization')->role($this->token);
    }

    public function curOrg()
    {
        return app('authorization')->curOrg($this->token);
    }

    public function refresh()
    {
        return app('authorization')->refresh($this->token);
    }
}