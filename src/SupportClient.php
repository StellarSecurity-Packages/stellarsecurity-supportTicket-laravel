<?php
// English comments only.

namespace StellarSecurity\SupportClient;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;

class SupportClient
{
    private string $baseUrl;
    private string $apiPrefix;
    private string $basicUser;
    private string $basicPass;
    private int $timeout;

    public function __construct(array $config = [])
    {
        $this->baseUrl = rtrim((string) Arr::get($config, 'base_url', ''), '/');
        $this->apiPrefix = '/' . ltrim((string) Arr::get($config, 'api_prefix', '/api/v1'), '/');
        $this->basicUser = (string) Arr::get($config, 'basic_user', '');
        $this->basicPass = (string) Arr::get($config, 'basic_pass', '');
        $this->timeout = (int) Arr::get($config, 'timeout', 15);
    }

    private function client(): PendingRequest
    {
        $req = Http::timeout($this->timeout)
            ->acceptJson();

        // Basic Auth: user/pass
        if ($this->basicUser !== '' || $this->basicPass !== '') {
            $req = $req->withBasicAuth($this->basicUser, $this->basicPass);
        }

        return $req;
    }

    private function url(string $path): string
    {
        return $this->baseUrl . $this->apiPrefix . '/' . ltrim($path, '/');
    }

    public function createTicket(array $data): array
    {
        $resp = $this->client()->post($this->url('support'), $data);
        return $this->decode($resp);
    }

    public function listTickets(array $filters = []): array
    {
        $resp = $this->client()->get($this->url('support'), $filters);
        return $this->decode($resp);
    }

    public function listTicketsByUserRef(string $userRef): array
    {
        $resp = $this->client()->get($this->url('support/user/' . rawurlencode($userRef)));
        return $this->decode($resp);
    }

    public function getTicket(string $ticketId): array
    {
        $resp = $this->client()->get($this->url('support/' . rawurlencode($ticketId)));
        return $this->decode($resp);
    }

    public function addMessage(string $ticketId, array $data): array
    {
        $resp = $this->client()->post($this->url('support/' . rawurlencode($ticketId) . '/messages'), $data);
        return $this->decode($resp);
    }

    public function updateStatus(string $ticketId, string $status): array
    {
        $resp = $this->client()->patch($this->url('support/' . rawurlencode($ticketId) . '/status'), [
            'status' => $status,
        ]);
        return $this->decode($resp);
    }

    private function decode(Response $resp): array
    {
        // Return API JSON, but include status when non-2xx
        $json = $resp->json();
        if (is_array($json)) {
            if (!$resp->successful()) {
                $json['_http_status'] = $resp->status();
            }
            return $json;
        }

        return [
            'ok' => $resp->successful(),
            '_http_status' => $resp->status(),
            'body' => $resp->body(),
        ];
    }
}
