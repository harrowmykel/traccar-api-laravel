<?php
namespace PiccmaQ\TraccarApiLaravel;

use Arr;
use PiccmaQ\TraccarApi\TraccarApi;

class TraccarLaravelClient
{
    private TraccarApi $client;

    public function __construct(string $account, array $config = [])
    {
        $auth_type = Arr::get($config, 'auth_type', config("traccar.servers.$account.auth_type"));
        $base_url = config("traccar.servers.$account.url");
        if ($auth_type === 'email') {
            $email = Arr::get($config, 'username', config("traccar.servers.$account.username"));
            $password = Arr::get($config, 'password', config("traccar.servers.$account.password"));
            $this->client = TraccarApi::withEmailPassword($email, $password)->setBaseUrl($base_url);
        } elseif ($auth_type === 'token') {
            $auth_token = Arr::get($config, 'auth_token', config("traccar.servers.$account.auth_token"));
            $this->client = TraccarApi::withAuthorizationToken($auth_token)->setBaseUrl($base_url);
        } else {
            $this->client = TraccarApi::noAuth()->setBaseUrl($base_url);
        }
    }

    public function getClient(): TraccarApi
    {
        return $this->client;
    }
}
