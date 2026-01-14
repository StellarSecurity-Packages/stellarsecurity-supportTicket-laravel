<?php
// English comments only.

namespace StellarSecurity\SupportClient\Facades;

use Illuminate\Support\Facades\Facade;
use StellarSecurity\SupportClient\SupportClient;

/**
 * @method static array createTicket(array $data)
 * @method static array listTickets(array $filters = [])
 * @method static array listTicketsByUserRef(string $userRef)
 * @method static array getTicket(string $ticketId)
 * @method static array addMessage(string $ticketId, array $data)
 * @method static array updateStatus(string $ticketId, string $status)
 */
class Support extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SupportClient::class;
    }
}
