# Stellar Security Support Client (Laravel)

A small Laravel package that calls the Stellar Support Ticket API over HTTP (Basic Auth).

## Config
Publish config:

```bash
php artisan vendor:publish --tag=stellarsecurity-support-client-config
```

Environment variables:

- STELLAR_SUPPORT_BASE_URL
- STELLAR_SUPPORT_BASIC_USER
- STELLAR_SUPPORT_BASIC_PASS
- STELLAR_SUPPORT_TIMEOUT (default 15)
- STELLAR_SUPPORT_API_PREFIX (default /api/v1)

## Usage

```php
use StellarSecurity\SupportClient\Facades\Support;

Support::createTicket([...]);
Support::listTicketsByUserRef('user-123');
Support::addMessage($ticketId, [...]);
```
