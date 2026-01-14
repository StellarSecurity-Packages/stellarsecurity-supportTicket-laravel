<?php

namespace StellarSecurity\SupportClient;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Http\UploadedFile;
use RuntimeException;

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

        if ($this->baseUrl === '') {
            throw new RuntimeException('SupportClient: base_url is required');
        }
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

    /**
     * Create ticket (JSON-only). Does NOT support file attachments.
     */
    public function createTicket(array $data): array
    {
        // If someone accidentally passes attachments here, they will not be sent.
        unset($data['attachments']);

        $resp = $this->client()->post($this->url('support'), $data);
        return $this->decode($resp);
    }

    /**
     * Create ticket with file attachments via multipart/form-data.
     *
     * @param array<string,mixed> $data
     * @param array<int,UploadedFile|string> $attachments  UploadedFile[] recommended. Strings = absolute file paths.
     */
    public function createTicketWithAttachments(array $data, array $attachments): array
    {
        $req = $this->client();

        // Encode metadata array as JSON string for multipart
        if (array_key_exists('metadata', $data) && is_array($data['metadata'])) {
            $data['metadata'] = json_encode($data['metadata'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

        // Attach files as attachments[]
        foreach ($attachments as $file) {
            if ($file instanceof UploadedFile) {
                $path = $file->getRealPath();
                if (!$path) {
                    continue;
                }

                $req = $req->attach(
                    'attachments[]',
                    file_get_contents($path),
                    $file->getClientOriginalName()
                );

                continue;
            }

            // Allow raw file paths too (absolute paths expected)
            if (is_string($file) && $file !== '' && is_file($file)) {
                $req = $req->attach(
                    'attachments[]',
                    file_get_contents($file),
                    basename($file)
                );
            }
        }

        // Post as multipart: fields + attachments[]
        $resp = $req->post($this->url('support'), $data);

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
