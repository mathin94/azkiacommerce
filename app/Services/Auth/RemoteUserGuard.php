<?php

namespace App\Services\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;

class RemoteUserGuard implements Guard
{
    protected $provider;
    protected $user;

    public function __construct(UserProvider $provider)
    {
        $this->provider = $provider;
    }

    public function check()
    {
        return !!$this->user();
    }

    public function guest()
    {
        return !$this->check();
    }

    public function user()
    {
        return $this->user;
    }

    public function id()
    {
        if ($this->check()) {
            return $this->user()->id;
        }
    }

    public function validate(array $credentials = [])
    {
        if (empty($credentials['email']) || empty($credentials['password'])) {
            return false;
        }

        $user = $this->provider->retrieveByCredentials($credentials);

        if (!is_null($user) && $this->provider->validateCredentials($user, $credentials)) {
            $this->setUser($user);

            return true;
        }

        return false;
    }

    public function setUser(Authenticatable $user)
    {
        $this->user = $user;

        return $this;
    }

    public function hasUser()
    {
        return !!$this->user();
    }

    public function attempt(array $credentials = [])
    {
        return $this->validate($credentials);
    }
}
