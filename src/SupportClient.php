<?php

namespace StellarSecurity\SupportClient;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class SupportClient
{
    public function __construct(private array $config) {}

    private function http(): PendingRequest
    {
        $baseUrl = rtrim((string) ($this->config['base_url'] ?? ''), '/');
        $prefix  = (string) ($this->config['prefix'] ?? '/api/v1');

        $user = (string) ($this->config['basic_user'] ?? '');
        $pass = (string) ($this->config['basic_pass'] ?? '');

        if ($baseUrl === '') {
            throw new RuntimeException('STELLAR_SUPPORT_BASE_URL is missing');
        }
        if ($user === '' || $pass === '') {
            throw new RuntimeException('STELLAR_SUPPORT_BASIC_USER/PASS is missing');
        }

        return Http::baseUrl($baseUrl . $prefix)
            ->timeout((int) ($this->config['timeout'] ?? 15))
            ->acceptJson()
            ->withBasicAuth($user, $pass);
    }

    public function post(string $path, array $data = []): array
    {
        $resp = $this->http()->post($path, $data);

        if (!$resp->successful()) {
            throw new RuntimeException("Support API POST {$path} failed: {$resp->status()} {$resp->body()}");
        }

        return (array) $resp->json();
    }

    public function get(string $path, array $query = []): array
    {
        $resp = $this->http()->get($path, $query);

        if (!$resp->successful()) {
            throw new RuntimeException("Support API GET {$path} failed: {$resp->status()} {$resp->body()}");
        }

        return (array) $resp->json();
    }

    public function patch(string $path, array $data = []): array
    {
        $resp = $this->http()->patch($path, $data);

        if (!$resp->successful()) {
            throw new RuntimeException("Support API PATCH {$path} failed: {$resp->status()} {$resp->body()}");
        }

        return (array) $resp->json();
    }
}
