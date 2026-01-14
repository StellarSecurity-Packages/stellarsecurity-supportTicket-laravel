<?php

namespace StellarSecurity\SupportClient;

use StellarSecurity\SupportClient\DTO\AddMessageRequest;
use StellarSecurity\SupportClient\DTO\CreateTicketRequest;

class Support
{
    public function __construct(private SupportClient $client) {}

    /**
     * POST /support
     */
    public function createTicket(array|CreateTicketRequest $request): array
    {
        $payload = $request instanceof CreateTicketRequest ? $request->toArray() : $request;
        return $this->client->post('/support', $payload);
    }

    /**
     * GET /support (supports filters: status, product, email)
     */
    public function listTickets(array $filters = []): array
    {
        return $this->client->get('/support', $filters);
    }

    /**
     * GET /support/{ticket}
     */
    public function getTicket(string $ticketId): array
    {
        return $this->client->get('/support/' . $ticketId);
    }

    /**
     * GET /support/user/{userRef}
     */
    public function listTicketsByUserRef(string $userRef): array
    {
        return $this->client->get('/support/user/' . urlencode($userRef));
    }

    /**
     * POST /support/{ticket}/messages
     */
    public function addMessage(string $ticketId, array|AddMessageRequest $request): array
    {
        $payload = $request instanceof AddMessageRequest ? $request->toArray() : $request;
        return $this->client->post('/support/' . $ticketId . '/messages', $payload);
    }

    /**
     * PATCH /support/{ticket}/status
     */
    public function updateStatus(string $ticketId, string $status): array
    {
        return $this->client->patch('/support/' . $ticketId . '/status', [
            'status' => $status,
        ]);
    }
}
