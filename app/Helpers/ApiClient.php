<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class ApiClient
{
    // Base URL from .env
    private static function baseUrl()
    {
        return env('API_BASE_URL', 'https://taskly-be.synodev.my.id/api');
    }

    // GET request
    public static function get(string $endpoint, string $token = null)
    {
        $url = self::baseUrl() . $endpoint;

        $client = $token ? Http::withToken($token) : Http::withoutVerifying();

        return $client->get($url);
    }

    // POST request
    public static function post(string $endpoint, array $data = [], string $token = null)
    {
        $url = self::baseUrl() . $endpoint;

        $client = $token ? Http::withToken($token) : Http::withoutVerifying();

        return $client->post($url, $data);
    }

    // PUT request
    public static function put(string $endpoint, array $data = [], string $token = null)
    {
        $url = self::baseUrl() . $endpoint;

        $client = $token ? Http::withToken($token) : Http::withoutVerifying();

        return $client->put($url, $data);
    }

    // DELETE request
    public static function delete(string $endpoint, string $token = null)
    {
        $url = self::baseUrl() . $endpoint;

        $client = $token ? Http::withToken($token) : Http::withoutVerifying();

        return $client->delete($url);
    }
}
